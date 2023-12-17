<?php

namespace Application\Helpers;

use Application\Models\Expression;
use Application\Models\Notification as ModelsNotification;
use System\Core\Model;
use System\Helpers\URL;

class MessageHelper
{
    public static function prepare($messages)
    {
        $userIds = [];
        foreach ($messages as &$message) 
        {
            $userIds[$message['sender_id']] = $message['sender_id'];
            $userIds[$message['receiver_id']] = $message['receiver_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        foreach( $messages as &$message )
        {
            $message['sender'] = isset($users[$message['sender_id']]) ? $users[$message['sender_id']] : null;
            $message['receiver'] = isset($users[$message['receiver_id']]) ? $users[$message['receiver_id']] : null;
        }

        return $messages;
    }
}