<?php

namespace Application\Commands;

use Application\Models\Queue as ModelsQueue;
use Exception;
use System\Core\CLICommand;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Message;
use Application\Models\Notification;
use Application\Models\Payment;
use Application\Models\Workshop;
use System\Core\Config;

class MessageCancel extends CLICommand
{
    public function run($params)
    {
        // Right now return this
        return;
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrders(null, Conversation::ENTITY_TYPE, null, Order::STATUS_APPROVED);

        $expiryTime = Config::get('Website')->message_expiry_time;

        foreach ($orders as $order) {
            /**
             * @var \Application\Models\Converastion
             */
            $convoM = Model::get('\Application\Models\Conversation');
            $convoInfo = $convoM->getById($order['entity_id']);

            /**
             * @var \Application\Models\Message
             */
            $messageM = Model::get('\Application\Models\Message');
            $messages = $messageM->getMessages($order['entity_id']);

            if ((time() - $convoInfo['created_at']) > $expiryTime && count($messages) < 2) {
                $convoM->update($convoInfo['id'], array(
                    'status' => Conversation::STATUS_CANCELED
                ));

                $orderM->update($order['id'], array(
                    'status' => Order::STATUS_CANCELED,
                    'remark' => 'order_expired'
                ));

                 /**
                 * @var \Application\Models\Payment
                 */
                $paymentsM = Model::get("\Application\Models\Payment");
                $payments = $paymentsM->all( $order['id'] );

                foreach( $payments as $payment )
                {
                    $paymentsM->update( $payment['id'], array('status' => Payment::STATUS_REFUND_INITIATED) );
                }

                $this->_inform($convoInfo);
            }
        }
    }

    public function _inform($convoInfo)
    {
        $arr = array(
            'sender_id' => 0,
            'receiver_id' => $convoInfo['owner_id'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_MESSAGE_CANCEL,
            'data' => json_encode($convoInfo),
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
            'user_id' => $convoInfo['owner_id'],
            'entity_info' => $convoInfo,
            'subject' => 'conversation_canceled',
            'view' => 'conversation_canceled'
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
            'receiver_id' => $convoInfo['created_by'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_MESSAGE_CANCEL,
            'data' => json_encode($convoInfo),
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
            'user_id' => $convoInfo['created_by'],
            'entity_info' => $convoInfo,
            'subject' => 'conversation_canceled',
            'view' => 'conversation_canceled'
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
