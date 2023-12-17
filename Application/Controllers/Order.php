<?php

namespace Application\Controllers;

use Application\Helpers\OrderHelper;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\HyperPay;
use Application\Models\Language;
use Application\Models\Order as ModelsOrder;
use Application\Models\Payment;
use Application\Models\Workshop;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Exceptions\SystemError;
use System\Core\Model;
use System\Core\Request as CoreRequest;
use System\core\Response;
use System\Responses\View;

class Order extends AuthController
{
    public function my( CoreRequest $request, Response $response )
    {
        $lang = $this->language;

        $currentYear = date('Y');
        $userInfo = $this->user->getInfo();
        $years = [];
        $limit = 10;
        /**
         * @var \Application\Models\Order
         */

        $orderM = Model::get("\Application\Models\Order");
        $orders = $orderM->getMyOrders( $userInfo['id'], $currentYear, null, false, $limit );
        $orderCount = $orderM->getMyOrders( $userInfo['id'], null, null, true );

        $orders = OrderHelper::prepare( $orders );

        // $firstYear = '';
        // foreach( $orders as $order )
        // {
        //     $firstYear = date('Y', $order['created_at']);
        // }
        // for ( $i=$currentYear; $i >= $firstYear; $i-- ) 
        // { 
        //     $years[] = $i;
        // }

        $view = new View();
        $view->set('Order/my',array(
            'orders' => $orders,
            'userInfo' => $userInfo,
            'years' => $years,
            'limit' => $limit,
            'orderCount' => $orderCount
        ));
        $view->prepend('header', [
            'title' => $lang('my_orders')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function view( CoreRequest $request, Response $response )
    {
        $lang = $this->language;
        $id = $request->param(0);

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('Application\Models\Order');
        $orderInfo = $orderM->take( $id );
        $orderInfo = OrderHelper::prepare( array($orderInfo) );

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('Application\Models\Payment');
        $payments = $paymentM->all( $id );

        $view = new View();
        $view->set('Order/view', [
            'orderInfo' => $orderInfo[0],
            'payments' => $payments
        ]);
        $view->prepend('header', [
            'title' => 'Order #'  . $id
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function requests( CoreRequest $request, Response $response )
    {
        $lang = $this->language;

        $type = $request->get('t');
        $type = !empty($type) ? $type : Workshop::ENTITY_TYPE;        

        switch( $type )
        {
            case Workshop::ENTITY_TYPE:
            case Call::ENTITY_TYPE:
            case Conversation::ENTITY_TYPE:
                break;
            default:
                throw new Error404;
        }


        $userInfo = $this->user->getInfo();

        $limit = 10;

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrders($userInfo['id'], $type, null, ModelsOrder::STATUS_PENDING, 0, $limit);        
        $orders = OrderHelper::prepare($orders);
        
        $workshopCount = $orderM->totalCount( $userInfo['id'], Workshop::ENTITY_TYPE );   
        $callCount = $orderM->totalCount( $userInfo['id'], Call::ENTITY_TYPE );   
        $messageCount = $orderM->totalCount( $userInfo['id'], Conversation::ENTITY_TYPE );

        $view = new View();
        $view->set('Order/requests', [
            'orders' => $orders,
            'workshopCount' => $workshopCount,
            'callCount' => $callCount,
            'messageCount' => $messageCount,
            'type' => $type,
            'limit' => $limit
        ]);
        $view->append('footer');
        $view->prepend('header', [
            'title' => $lang('requests')
        ]);
        
        $response->set($view);
    }

    public function complete( CoreRequest $request, Response $response )
    {
        $id = $request->get('id', null);
        @list( $cId, $orderId ) = $this->session->take('order');

        /**
         * @var ModelsOrder
         */
        $orderM = Model::get(ModelsOrder::class);
        $order = $orderM->take($orderId);

        if ( $order['payable'] == 0 ){
            $order = [$cId, $orderId, [ 'payment' => Payment::METHOD_FREE ]];
            $this->session->put('order', $order);
            throw new Redirect('checkout/success');
        }

        if ( $cId !== $id ) throw new Error404;

         /**
         * @var HyperPay
         */
        $hyperPay = Model::get(HyperPay::class);
        $response = $hyperPay->request($id, $order['payment_method']);
        $response = json_decode($response, true);

        $data = [$cId, $orderId, $response];
        $this->session->put('order', $data);

        if ( !$response || !isset($response['id']) ) throw new Redirect('checkout/fail');

        if ( $response['result']['code'] !== "000.100.110" && $response['result']['code'] !== '000.000.000' ) throw new Redirect('checkout/fail');


        if ($order['payment_method'] == Payment::METHOD_APPLE_PAY) {
            $paymentBrand = $response['paymentBrand'];
            $orderM->updateOrderInfoForApplePay($order, $paymentBrand, $response);
        }

        throw new Redirect('checkout/success');
    }

    public function cancel( CoreRequest $request, Response $response )
    {
        $id = $request->param(0);

        /**
         * @var ModelsOrder
         */
        $orderM = Model::get(ModelsOrder::class);
        $order = $orderM->take($id);
        if ( empty( $order ) ) throw new Error404;

        $userInfo = $this->user->getInfo();

        if ( $order['user_id'] !== $userInfo['id'] )
            throw new Error404;

        $data = $this->hooks->dispatch('order.user_cancel_validate', $order)->now();
        if ( !isset($data['\Application\Hooks\Order::userCancelValidate']) )
            throw new SystemError("Hook not found");

        $data = $data['\Application\Hooks\Order::userCancelValidate'];

        if ( !$data['isValid'] )
        {
            $this->session->put('order_error', $data['msg']);
            throw new Redirect('order/my');
        }

        // else we need to cancel the order
        $orderM->update($order['id'], [
            'status' => ModelsOrder::STATUS_CANCELED,
            'remark' => 'order_advisor_canceled'
        ]);

        $this->hooks->dispatch('order.on_user_cancel', $order )->later();

        $lang = Model::get(Language::class);

        $this->session->put('order_error', $lang('order_cancel_successful'));

        throw new Redirect('order/my');
    }
}