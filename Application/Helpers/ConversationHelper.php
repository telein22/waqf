<?php

namespace Application\Helpers;

use Application\Models\Conversation;
use Application\Models\Message;
use System\Core\Config;
use System\Core\Model;

class ConversationHelper
{
    public static function prepare( $data )
    {
        if ( empty($data) ) return $data;

        $userIds = [];
        $conversationIds = [];
        $messageIds = [];

        foreach ( $data as $item )
        {
            $userIds[$item['owner_id']] = $item['owner_id'];
            $userIds[$item['created_by']] = $item['created_by'];
            $conversationIds[$item['id']] = $item['id'];

            if ( $item['last_message_id'] )
            {
                $messageIds[$item['last_message_id']] = $item['last_message_id'];
            }
        }

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');
        $participants = $partiM->getByEntities([ Conversation::ENTITY_TYPE => $conversationIds ]);                
        $participants = ParticipantHelper::prepareByEntity($participants);

        /**
         * @var Message
         */
        $messageM = Model::get(Message::class);
        $messages = $messageM->getInfoByIds($messageIds);

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        foreach ( $data as & $item )
        {
            $item['owner'] = isset( $users[$item['owner_id']] ) ? $users[$item['owner_id']] : null;
            unset($item['owner_id']); // Remove owner id not needed any more.
            $item['creator'] = isset( $users[$item['created_by']] ) ? $users[$item['created_by']] : null;
            unset($item['created_by']); // Remove owner id not needed any more.

            $p = [];
            if (
                isset($participants[Conversation::ENTITY_TYPE]) &&
                isset($participants[Conversation::ENTITY_TYPE][$item['id']])
            ) {
                $p = $participants[Conversation::ENTITY_TYPE][$item['id']];
            }
            $item['participants'] = $p;

            $timeout = Config::get('Website')->conversation_timeout;
            $remain = DateHelper::remains($item['created_at'] + $timeout );
            $item['remaining'] = $remain[0] . 'h ' . $remain[1] . 'm ';
            $item['last_message'] = isset($messages[$item['last_message_id']]) ? $messages[$item['last_message_id']] : null;

        }

        return $data;
    }

    public static function isExpired( $time )
    {
        $timeout = Config::get('Website')->conversation_timeout;
        $time = $time + $timeout;
        return $time < time();
    }
}