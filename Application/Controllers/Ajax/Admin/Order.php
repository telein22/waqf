<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Helpers\MessageHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Conversation;
use Application\Models\Order as ModelsOrder;
use Application\Models\UserSettings;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\Responses\View;

class Order extends AuthController
{
    public function charityList( Request $request )
    {
        $id = $request->post('eId');
        $allCharities = array();

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orderInfo = $orderM->take($id);

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');

        if( $orderInfo['for_charity'] != '[]' && $orderInfo['for_charity'] != '0' )
        {
            $charities = json_decode($orderInfo['for_charity'], true);

            $charities = $charityM->getByIds($charities);

            foreach( $charities as $charity )
            {
                $allCharities[] = $charity;
            }
        }

        throw new ResponseJSON('success', $allCharities);
    }

    public function transferInfo( Request $request )
    {
        $id = $request->post('id');

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orderInfo = $orderM->take($id);

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('Application\Models\Payment');
        $payments = $paymentM->all( $id );

        $userInfo = $this->user->getInfo();
        // Find all messages.
        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $messages = $msgM->getMessages($orderInfo['id']);
        $messages = MessageHelper::prepare( $messages );


        $view = new View();
        $view->set('Admin/Billings/details_item', [
            'user' => $userInfo,
            'order' => $orderInfo,
            'payments' => $payments,
        ]);

        throw new ResponseJSON('success', $view->content());
    }

    public function showDetails( Request $request )
    {
        $id = $request->post('id');

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orderInfo = $orderM->take($id);

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('Application\Models\Payment');
        $payments = $paymentM->all( $id );

        $userInfo = $this->user->getInfo();
        // Find all messages.
        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $messages = $msgM->getMessages($orderInfo['id']);
        $messages = MessageHelper::prepare( $messages );

        /**
         * @var UserSettings
         */
        $userSM = Model::get(UserSettings::class);
        $bankDetails = [];
        $bankDetails[] = $userSM->take($orderInfo['entity_owner_id'], UserSettings::KEY_BANK1);
        $bankDetails[] = $userSM->take($orderInfo['entity_owner_id'], UserSettings::KEY_BANK2);
        $bankDetails[] = $userSM->take($orderInfo['entity_owner_id'], UserSettings::KEY_BANK3);


        $view = new View();
        $view->set('Admin/Billings/details_item', [
            'user' => $userInfo,
            'order' => $orderInfo,
            'payments' => $payments,
            'bankDetails' => $bankDetails
        ]);

        throw new ResponseJSON('success', $view->content());
    }

    public function cancel( Request $request )
    {
        $id = $request->post('id');

        /**
         * @var ModelsOrder
         */
        $orderM = Model::get(ModelsOrder::class);
        $order = $orderM->getInfoByIds($id);
        if ( empty($order) ) throw new ResponseJSON('error');
        $order = $order[$id];

        $orderM->update($order['id'], [
            'status' => ModelsOrder::STATUS_CANCELED,
            'remark' => 'order_admin_canceled'
        ]);

        $order['status'] = ModelsOrder::STATUS_CANCELED;

        $this->hooks->dispatch('admin.order.on_status_cancel', $order)->later();

        throw new ResponseJSON('success');
    }

    public function hold( Request $request )
    {
        $id = $request->post('id');
        $value = $request->post('value');

        if ( !in_array($value, [1, 0]) ) throw new ResponseJSON('error', 'invalidParam');

        /**
         * @var ModelsOrder
         */
        $orderM = Model::get(ModelsOrder::class);
        $order = $orderM->getInfoByIds($id);
        if ( empty($order) ) throw new ResponseJSON('error');
        $order = $order[$id];

        $orderM->update($order['id'], [
            'in_hold' => $value
        ]);

        $order['in_hold'] = $value;

        $this->hooks->dispatch('admin.order.on_status_hold', $order)->later();

        throw new ResponseJSON('success');
    }
}