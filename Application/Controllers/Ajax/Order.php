<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\OrderHelper;
use Application\Helpers\PaymentHelper;
use Application\Helpers\WorkshopHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Coupons;
use Application\Models\HyperPay;
use Application\Models\Order as ModelsOrder;
use Application\Models\Payment;
use Application\Models\Workshop;
use Application\Models\Order as OrderModel;
use Application\Models\Participant;
use Application\Values\Coupon;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Responses\View;

class Order extends AuthController
{
    public function create( Request $request )
    {
        $id = $request->post('id');
        $type = $request->post('type');
        $amount = $request->post('amount');
        $payable = $request->post('payable');
        $payable = (float) str_replace(',', '', $payable);

        $couponCode = $request->post('coupon');
        $couponAmount = $request->post('coupon_amount');
        $vat = $request->post('vat_amount');
        $couponCode = empty($couponCode) ? null : $couponCode;
        $couponAmount = empty($couponAmount) ? 0 : $couponAmount;

        $advisorAmount = $amount;
        $couponObjectValue = null;
        if ($couponCode) {
            @list( $id, $type, $couponInfo ) = $this->session->take('checkout');
            $couponM = Model::get(Coupons::class);
            $couponObjectValue = new Coupon($couponInfo['code'], $couponInfo['type'], $couponInfo['amount']);

            $advisorAmount = $couponM->getAmountAfterCoupon(
                $amount,
                $couponObjectValue
            );
        }

        $userInfo = $this->user->getInfo();

        $data = $this->_validateEntity($id, $type);
        $isValid = true;

        switch ( $type ) {
            case Workshop::ENTITY_TYPE:
                
                $workshopM = Model::get(Workshop::class);
                $workshopInfo = $workshopM->getInfoById( $id );

                $isExpired = WorkshopHelper::isExpired($workshopInfo['date'], $workshopInfo['duration']);

                $partiM = Model::get(Participant::class);
                $count = $partiM->count($id, Workshop::ENTITY_TYPE);
                $count = isset($count[$id]) ? $count[$id] : 0;

                if( $isExpired || $workshopInfo['capacity'] <= $count )
                {
                    $isValid = false;
                }

                break;
            default:
                # code...
                break;
        }

        if( !$isValid ) throw new ResponseJSON('error', "Workshop has already expired");

        // one entity validation is done
        // we can check if the cards are valid.
        $paymentMode = $request->post('payM');

        // var_dump($_POST);exit;

        switch( $paymentMode )
        {
            case Payment::METHOD_VISA:
            case Payment::METHOD_APPLE_PAY:
            case Payment::METHOD_STC:
            case Payment::METHOD_MADA:
                $method = $paymentMode;
                break;
            default:
                // Free user cant user coupon
                $couponCode = null;
                $method = Payment::METHOD_FREE;
        }

        $finalAmount = $payable - PaymentHelper::getGetWayPercentageCut($payable, $method);

        $orderM = Model::get(OrderModel::class);
        $order = $orderM->create([
            'user_id' => $userInfo['id'],
            'entity_owner_id' => $data['owner_id'],
            'entity_id' => $id,
            'entity_type' => $type,
            'amount' => $amount,
            'payable' => $payable,
            'final_amount' => $finalAmount,
            'advisor_amount' => $advisorAmount,
            'admin_amount' => ($finalAmount - $advisorAmount),
            'used_coupon' => $couponCode,
            'coupon_amount' => $couponAmount,
            'vat' => $vat,
            'status' => ModelsOrder::STATUS_INCOMPLETE,
            'for_charity' => $data['for_charity'],
            'payment_method' => $method,
            'in_hold' => 0,
            'created_at' => time(),
            'remark' => ''
        ]);

        
        if ( !$order ) throw new ResponseJSON('error', "Internal server error.");

        $hyperPay = Model::get(HyperPay::class);
        $payable = number_format($payable, 2);
        $response = $hyperPay->prepareCheckout($payable, $order, $userInfo, $paymentMode);
        $response = json_decode($response, true);

        if ( !$response || !isset($response['id']) ) throw new ResponseJSON('error', "Api error: 1");

        // Now redirect to success url
        // But first submit the session
        $this->session->put('order', [
            $response['id'],
            $order
        ]);
        $orderInfo = $orderM->take( $order );

        // Find order items
        $this->hooks->dispatch('order.on_create', $orderInfo)->later();
        
        throw new ResponseJSON('success', URL::full("checkout/pay"));
    }

    public function moreOrders( Request $request )
    {
        $fromId = $request->post('fromId');
        $fromId = !empty($fromId) ? $fromId : null;
        $currentYear = date('Y');

        $userInfo = $this->user->getInfo();

        $limit = 10;

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get("\Application\Models\Order");
        $orders = $orderM->getMyOrders($userInfo['id'], $currentYear, $fromId, false, $limit);
        $orders = OrderHelper::prepare($orders);

        $output = [];
        foreach ($orders as $order) {

            $view = new View();
            $view->set('Order/my_item', [
                'order' => $order,
            ]);

            $output[] = array(
                'orderId' => $order['id'],
                'order' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'orders' => $output,
            'dataAvl' => count($orders) == $limit
        ));
    }

    public function moreRequest( Request $request )
    {
        $skip = $request->post('skip');
        $type = $request->post('t');

        $type = !empty($type) ? $type : Workshop::ENTITY_TYPE;

        switch( $type )
        {
            case Workshop::ENTITY_TYPE:
                break;
            default:
                throw new ResponseJSON('error', "Invalid type");
        }

        $userInfo = $this->user->getInfo();

        $limit = 10;

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrders($userInfo['id'], $type, null, ModelsOrder::STATUS_PENDING, $skip, $limit);
        $orders = OrderHelper::prepare($orders);

        $output = [];

        foreach ( $orders as $order )
        {
            $view = new View();
            $view->set('Order/request_item', [
                'item' => $order
            ]);

            $output[] = $view->content();
        }

        throw new ResponseJSON('success', [
            'skip' => $skip + $limit,
            'dataAvl' => $limit == count($orders),
            'requests' =>  $output
        ]);
    }

    public function accept( Request $request, Response $response )
    {
        $id = $request->post('id');

        // Find order for this id.
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $order = $orderM->take($id);

        if ( empty($order) ) throw new ResponseJSON('error', "Invalid request");

        // first check if is valid for approve
        $data = $this->hooks->dispatch('order.accept_validation', $order)->now();
        if ( !isset($data['\Application\Hooks\Order::acceptValidate']) ) throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Order::acceptValidate'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data['msg']);

        // else order is valid and process the accept
        $orderM->update($order['id'], [
            'status' => ModelsOrder::STATUS_APPROVED
        ]);

        // fire hook one the order update is done
        $this->hooks->dispatch('order.on_accept', $order)->later();

        throw new ResponseJSON('success');
    }

    public function decline( Request $request, Response $response )
    {
        $id = $request->post('id');

        // Find order for this id.
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $order = $orderM->take($id);

        if ( empty($order) ) throw new ResponseJSON('error', "Invalid request");

        // first check if is valid for approve
        $data = $this->hooks->dispatch('order.cancel_validation', $order)->now();
        if ( !isset($data['\Application\Hooks\Order::cancelValidate']) ) throw new ResponseJSON('error', "Hook not found");
        $data = $data['\Application\Hooks\Order::cancelValidate'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data['msg']);

        // else order is valid and process the accept
        $orderM->update($order['id'], [
            'status' => ModelsOrder::STATUS_CANCELED
        ]);

        // fire hook one the order update is done
        $this->hooks->dispatch('order.on_cancel', $order)->later();

        throw new ResponseJSON('success');
    }

    private function _validateEntity( $id, $type )
    {
        $lang = $this->language;

        $output = [];

        switch( $type )
        {
            case Workshop::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Workshop
                 */
                $workM = Model::get('\Application\Models\Workshop');
                $workshop = $workM->getInfoByIds($id);
                if ( empty($workshop) ) throw new ResponseJSON('error', $lang('cant_find_entity'));
                $workshop = $workshop[$id];

                // else we check of slot.
                /**
                 * @var \Application\Models\Participant
                 */
                $partiM = Model::get('\Application\Models\Participant');
                $count = $partiM->count($id, $type);
                $count = isset($count[$id]) ? $count[$id] : 0;

                if ( $count >= $workshop['capacity'] ) throw new ResponseJSON('error', $lang('slot_full'));

//                $isExpired = WorkshopHelper::orderExpired($workshop['date']);
//                if ( $isExpired ) throw new ResponseJSON('error', $lang('checkout_workshop_expired'));

                $data = array(
                    'item' => $workshop,
                    'for_charity' => $workshop['charity'],
                    'owner_id' => $workshop['user_id']
                );
                break;
            case Call::ENTITY_TYPE:
                   /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $item = $callM->getById($id);
                if ( empty($item) ) throw new ResponseJSON('error', $lang('cant_find_entity'));

//                $isExpired = WorkshopHelper::orderExpired($item['date']);
//                if ( $isExpired ) throw new ResponseJSON('error', $lang('checkout_call_expired'));

                $data = array(
                    'item' => $item,
                    'for_charity' => $item['charity'],
                    'owner_id' => $item['owner_id']
                );
                break;

            case Conversation::ENTITY_TYPE:
                /*
                * @var \Application\Models\Conversation
                */
                $conM = Model::get('\Application\Models\Conversation');
                $item = $conM->getById($id);
                if ( empty($item) ) throw new Error404;

                $data = array(
                    'item' => $item,
                    'for_charity' => 0,
                    'owner_id' => $item['owner_id']
                );
                break;
        }

        return $data;
    }

}