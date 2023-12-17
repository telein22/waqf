<?php

namespace Application\Commands;

use Application\Helpers\ConversationHelper;
use Application\Models\Queue as ModelsQueue;
use Exception;
use System\Core\CLICommand;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Message;
use Application\Models\Notification;
use Application\Models\Reminder;
use Application\Models\Workshop;
use System\Core\Config;

class MessageReminder extends CLICommand
{
    public function run( $params )
    {
       /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrders(null, Conversation::ENTITY_TYPE, null, Order::STATUS_APPROVED);

        $reminderTime = Config::get('Website')->message_reminder_time;

        foreach( $orders as $order )
        {
            $reminderM = Model::get(Reminder::class);            
            /**
             * @var \Application\Models\Conversation
             */
            $convoM = Model::get('\Application\Models\Conversation');
            /**
             * @var \Application\Models\Message
             */
            $messageM = Model::get('\Application\Models\Message');

            $reminded = $reminderM->getByEntity( $order['entity_id'], $order['entity_type'] );
            // Return if already reminded.
            if( !empty($reminded) ) continue;

            $convoInfo = $convoM->getById( $order['entity_id'] );            

            $messages = $messageM->getMessages( $order['entity_id'] );


            if( ( $convoInfo['created_at'] + $reminderTime < time() ) && count($messages) < 2 )
            {
                $reminderM->create(array(
                    'entity_id' => $order['entity_id'],
                    'entity_type' => $order['entity_type']
                ));
                
                // First check if the conversation status is current or not            
                if (  $convoInfo['status'] != Conversation::STATUS_CURRENT ) continue;                
                // If the conversation is already expired don't remind.                
                if ( ConversationHelper::isExpired($convoInfo['created_at']) ) continue;
                    
                $this->_inform( $convoInfo );
            }

        }

    }

    public function _inform( $convoInfo )
    {
        $arr = array(
            'sender_id' => 0,
            'receiver_id' => $convoInfo['owner_id'],
            'type' => Notification::TYPE_SERVICE,
            'action_type' => Notification::CRON_MESSAGE_REMINDER,
            'data' => json_encode($convoInfo),
            'read' => 0,
            'sent' => 0
        );

        $json = json_encode($arr);

        // $queueM = Model::get('\Application\Models\Queue');
        // $queueM->create(array(
        //     'type' => ModelsQueue::TYPE_NOTIFICATION,
        //     'data' => $json,
        //     'priority' => 5,
        //     'created_at' => time()
        // ));

        $notiM = Model::get('\Application\Models\Notification');
        $data = array_merge($arr, [ 'created_at' => time() ]);
        $notiM->create($data);
    }
}