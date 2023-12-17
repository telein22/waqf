<?php

namespace Application\Commands;

use Application\Helpers\WorkshopHelper;
use Application\Models\Queue as ModelsQueue;
use System\Core\CLICommand;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Call;
use Application\Models\Notification;
use Application\Models\Payment;

class callCancel extends CLICommand
{
    public function run( $params )
    {
        // Right now return this
        return;

        $orderM = Model::get(Order::class);
        $callM = Model::get(Call::class);
        $orders = $orderM->getOrders(null, Call::ENTITY_TYPE, null, Order::STATUS_APPROVED);
        $paymentsM = Model::get(Payment::class);

        foreach ($orders as $order) {
            $callInfo = $callM->getById($order['entity_id']);
            $isExpired = WorkshopHelper::isExpired($callInfo['date'], $callInfo['duration']);
            $started = $callInfo['status'] == Call::STATUS_CURRENT;

            if (!$started && $isExpired) {
                $callM->update($callInfo['id'], [
                    'status' => Call::STATUS_CANCELED
                ]);

                $orderM->update($order['id'], [
                    'status' => Order::STATUS_CANCELED,
                    'remark' => 'order_expired'
                ]);


                $payments = $paymentsM->all($order['id']);

                foreach ($payments as $payment) {
                    $paymentsM->update($payment['id'], ['status' => Payment::STATUS_REFUND_INITIATED]);
                }

//                $this->_inform($callInfo);
            }
        }

    }

    public function _inform( $callInfo )
    {
        $arr = array(
            'sender_id' => 0,
            'receiver_id' => $callInfo['owner_id'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_CALL_AUTO_CANCELLED,
            'data' => json_encode($callInfo),
            'read' => 0,
            'sent' => 0
        );

        $json = json_encode($arr);

        $queueM = Model::get('\Application\Models\Queue');
        $queueM->create(array(
            'type' => ModelsQueue::TYPE_NOTIFICATION,
            'data' => $json,
            'priority' => 5,
            'created_at' => time()
        ));

        $arr = array(
            'user_id' => $callInfo['owner_id'],
            'entity_info' => $callInfo,
            'subject' => 'call_canceled',
            'view' => 'call_canceled'
        );

        $json = json_encode($arr);

        $queueM->create(array(
            'type' => ModelsQueue::TYPE_EMAIL,
            'data' => $json,
            'priority' => 5,
            'created_at' => time()
        ));

        $arr = array(
            'sender_id' => 0,
            'receiver_id' => $callInfo['created_by'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_CALL_AUTO_CANCELLED,
            'data' => json_encode($callInfo),
            'read' => 0,
            'sent' => 0
        );

        $json = json_encode($arr);

        $queueM = Model::get('\Application\Models\Queue');
        $queueM->create(array(
            'type' => ModelsQueue::TYPE_NOTIFICATION,
            'data' => $json,
            'priority' => 5,
            'created_at' => time()
        ));

        $arr = array(
            'user_id' => $callInfo['created_by'],
            'entity_info' => $callInfo,
            'subject' => 'call_canceled',
            'view' => 'call_canceled'
        );

        $json = json_encode($arr);

        $queueM->create(array(
            'type' => ModelsQueue::TYPE_EMAIL,
            'data' => $json,
            'priority' => 5,
            'created_at' => time()
        ));
    }
}