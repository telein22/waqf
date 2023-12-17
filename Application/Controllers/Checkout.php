<?php

namespace Application\Controllers;

use Application\Helpers\CallHelper;
use Application\Helpers\ConversationHelper;
use Application\Helpers\WorkshopHelper;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Invite;
use Application\Models\Order;
use Application\Models\Payment;
use Application\Models\Settings;
use Application\Models\UserSettings;
use Application\Models\Workshop;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Checkout extends AuthController
{
    public function index( Request $request, Response $response )
    {
        @list( $id, $type, $coupon ) = $this->session->take('checkout');       
        if ( empty($id) || empty($type) ) throw new Error404();

        $lang = $this->language;

        list($item, $price, $name) = $this->_getItem($id, $type);

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Invite
         */
        $inviteM = Model::get('\Application\Models\Invite');
        $inviteType = $inviteM->isInvited($userInfo['id'], $id, $type);

        /**
         * @var \Application\Models\Settings
         */
        $sM =  Model::get('\Application\Models\Settings');
        $vat = $sM->take(Settings::KEY_VAT, 0);
        $platform_fees = $sM->take(Settings::KEY_PLATFORM_FEES, 0);

        $view = new View();
        $view->set('Checkout/index', [
            'item' => $item,
            'price' => $price,
            'type' => $type,
            'name' => $name,
            'coupon' => $coupon,
            'id' => $id,
            'inviteType' => $inviteType,
            'vat' => $vat,
            'platform_fees' => $platform_fees,
        ]);
        $view->prepend('header', [
            'title' => $lang('checkout')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function pay( Request $request, Response $response )
    {
        list( $token, $orderId ) = $this->session->take('order');

        if ( empty($token) || empty($orderId) ) throw new Redirect('');

        /**
         * @var Order
         */
        $orderM = Model::get(Order::class);
        $order = $orderM->take($orderId);

        if ( $order['payable'] == 0 ) throw new Redirect('order/complete');

        if ( empty($order) ) throw new Redirect('');

        $lang = $this->language;
        $userInfo = $this->user->getInfo();

        /**
         * @var UserSettings
         */
        $userSM = Model::get(UserSettings::class);

        $billingAddress = $userSM->take($userInfo['id'], UserSettings::KEY_BILLING_ADDRESS, '{}');

        $view = new View();
        $view->set('Checkout/pay', [
            'order' => $order,
            'token' => $token,
            'billingAddress' => $billingAddress
        ]);

        $view->prepend('header', [
            'title' => $lang('checkout')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function success( Request $request, Response $response )
    {
        @list ( $cId, $orderId, $payment ) = $this->session->take('order');        
        if ( !$cId || !$orderId || !$payment  ) throw new Error404;
        
        $lang = $this->language;

        $this->session->delete('order');
        $this->session->delete('checkout');
        
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $order = $orderM->take($orderId);

        if ( empty($order) ) throw new Error404;

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('\Application\Models\Payment');
        $paymentM->create(array(
            'order_id' => $order['id'],
            'method' => $order['payment_method'],
            'payable' => $order['amount'],
            'paid' => $order['payable'],
            'txn_token' => $cId,
            'status' => Payment::STATUS_SUCCESS,
            'gateway_data' => json_encode($payment),
            'created_at' => time()
        ));

        // Make order status pending
        $orderM->update($order['id'], [
            'status' => Order::STATUS_PENDING
        ]);

        /**
         * Free can happen for multiple reasons
         * For example 100% or coupon that exceed more than actual amount makes the 
         * transaction free
         */
        if ( $payment != Payment::METHOD_FREE )
        {
            /**
             * @var UserSettings
             */
            $userSM = Model::get(UserSettings::class);
            $userSM->put($userInfo['id'], UserSettings::KEY_BILLING_ADDRESS, json_encode($payment['billing']));
        }

        // Find order items
        $this->hooks->dispatch('order.success', $order)->later();

        $view = new View();
        $view->set('Checkout/success', [
            'order' => $order
        ]);
        $view->prepend('header', [
            'title' => $lang('checkout_success')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function fail( Request $request, Response $response )
    {
        @list ( $cId, $orderId, $payment ) = $this->session->take('order');        
        if ( !$cId || !$orderId || !$payment  ) throw new Error404;
        
        $lang = $this->language;

        $this->session->delete('order');
        $this->session->delete('checkout');
        
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $order = $orderM->take($orderId);

        if ( empty($order) ) throw new Error404;

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('\Application\Models\Payment');
        $paymentM->create(array(
            'order_id' => $order['id'],
            'method' => $order['payment_method'],
            'payable' => $order['amount'],
            'paid' => $order['payable'],
            'txn_token' => $cId,
            'status' => Payment::STATUS_FAILED,
            'gateway_data' => json_encode($payment),
            'created_at' => time()
        ));

        // Make order status pending        

        // Find order items
        $this->hooks->dispatch('order.fail', $order)->later();

        $view = new View();
        $view->set('Checkout/fail', [
            'order' => $order
        ]);
        $view->prepend('header', [
            'title' => $lang('checkout_fail')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    private function _getItem( $id, $type )
    {
        $lang = $this->language;

        $item = array();
        switch( $type )
        {
            case Workshop::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Workshop
                 */
                $workM = Model::get('\Application\Models\Workshop');
                $item = $workM->getInfoByIds($id);
                if ( empty($item) ) throw new Error404;

                // else item is available
                $item = WorkshopHelper::prepare($item);

                $item = $item[$id];
                $price = $item['price'];
                $name = $item['name'];

                break;
            case Call::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $item = $callM->getById($id);                
                if ( empty($item) ) throw new Error404;
                $item = CallHelper::prepare([$item], null);

                $item = $item[0];
                $price = $item['price'];
                $name = $lang('calls');

                break;
            case Conversation::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Conversation
                 */
                $conM = Model::get('\Application\Models\Conversation');
                $item = $conM->getById($id);                
                if ( empty($item) ) throw new Error404;
                $item = ConversationHelper::prepare([$item]);
                $item = $item[0];

                $ownerId = $item['owner']['id'];
                /**
                 * @var \Application\Models\UserSettings
                 */
                $userSM = Model::get('\Application\Models\UserSettings');
                $price = $userSM->take($ownerId, UserSettings::KEY_MESSAGING_PRICE, 1);

                $name = $lang('messaging');

                break;
        }

        if ( empty($item) ) throw new Error404;
        else return [$item, $price, $name];
    }

}