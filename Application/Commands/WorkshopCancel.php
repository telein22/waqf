<?php

namespace Application\Commands;

use Application\Helpers\WorkshopHelper;
use Application\Models\Queue as ModelsQueue;
use System\Core\CLICommand;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Notification;
use Application\Models\Payment;
use Application\Models\Workshop;

class WorkshopCancel extends CLICommand
{
    public function run( $params )
    {
        // Right now return this
        return;

        $orderM = Model::get(Order::class);
        $orders = $orderM->getOrders(null, Workshop::ENTITY_TYPE, null, Order::STATUS_APPROVED);
        $workshopM = Model::get(Workshop::class);
        $paymentsM = Model::get(Payment::class);

        foreach ($orders as $order) {
            $workshopInfo = $workshopM->getInfoById($order['entity_id']);
            $isExpired = WorkshopHelper::isExpired($workshopInfo['date'], $workshopInfo['duration']);
            $started = $workshopInfo['status'] == Workshop::STATUS_CURRENT;

            if (!$started && $isExpired) {
                $workshopM->update($workshopInfo['id'], [
                    'status' => Workshop::STATUS_CANCELED
                ]);

                $orderM->update($order['id'], [
                    'status' => Order::STATUS_CANCELED,
                    'remark' => 'order_expired'
                ]);

                $payments = $paymentsM->all($order['id']);

                foreach ($payments as $payment) {
                    $paymentsM->update($payment['id'], ['status' => Payment::STATUS_REFUND_INITIATED]);
                }

//                $this->_inform($workshopInfo);
            }
        }
    }

    public function _inform( $workshopInfo )
    {
        $arr = array(
            'sender_id' => 0,
            'receiver_id' => $workshopInfo['user_id'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_WORKSHOP_AUTO_CANCELED,
            'data' => json_encode($workshopInfo),
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
            'user_id' => $workshopInfo['user_id'],
            'entity_info' => $workshopInfo,
            'subject' => 'workshop_canceled',
            'view' => 'workshop_canceled'
        );

        $json = json_encode($arr);

        $queueM->create(array(
            'type' => ModelsQueue::TYPE_EMAIL,
            'data' => $json,
            'priority' => 5,
            'created_at' => time()
        ));

        $participantM = Model::get('\Application\Models\Participant');
        $participants = $participantM->all( $workshopInfo['id'], Workshop::ENTITY_TYPE );

        foreach( $participants as $participant )
        {
            $arr = array(
                'sender_id' => 0,
                'receiver_id' => $participant['user_id'],
                'type' => Notification::TYPE_SERVICE,
                'action_type' => Notification::CRON_WORKSHOP_AUTO_CANCELED,
                'data' => json_encode($workshopInfo),
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
                'user_id' => $participant['user_id'],
                'entity_info' => $workshopInfo,
                'subject' => 'workshop_canceled',
                'view' => 'workshop_canceled'
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
}