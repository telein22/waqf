<?php

namespace Application\Helpers;

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Expression;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

class ReviewHelper
{
    public static function prepare( $data )
    {
        if ( empty($data) ) return $data;

        $workshopIds = [];
        $callIds = [];
        $conversationIds = [];
        $userIds = [];

        foreach ( $data as $item )
        {
            switch( $item['entity_type'] )
            {
                case Workshop::ENTITY_TYPE:
                    $workshopIds[$item['entity_id']] = $item['entity_id'];
                    break;
                case Call::ENTITY_TYPE:
                    $callIds[$item['entity_id']] = $item['entity_id'];
                    break;
                case Conversation::ENTITY_TYPE:
                    $conversationIds[$item['entity_id']] = $item['entity_id'];
                    break;
            }

            $userIds[$item['entity_owner_id']] = $item['entity_owner_id'];
        }

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshops = $workM->getInfoByIds($workshopIds);

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $calls = $callM->getInfoByIds($callIds);

        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');
        $conversations = $conM->getInfoByIds($callIds);

         /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds( $userIds );

        foreach ( $data as &$item )
        {
            switch( $item['entity_type'] )
            {
                case Workshop::ENTITY_TYPE:
                    $item['entity_name'] = isset($workshops[$item['entity_id']]) ? $workshops[$item['entity_id']]['name'] : null;
                    break;
                case Call::ENTITY_TYPE:
                    $item['entity_name'] = isset($calls[$item['entity_id']]) ? $calls[$item['entity_id']]['name'] : null;
                    break;
                case Conversation::ENTITY_TYPE:

                    // We can assign entity name as entity owner id name
                    // as the service will be given by this user.
                    $item['entity_name'] = isset($users[$item['entity_owner_id']]) ? $users[$item['entity_owner_id']]['name'] : null;                    
                    $item['entity_url'] = isset($users[$item['entity_owner_id']]) ? URL::full('profile/' . $users[$item['entity_owner_id']]['id']) : null;
                    break;
            }

            $item['user'] = isset($users[$item['entity_owner_id']]) ? $users[$item['entity_owner_id']] : null;


        }

        return $data;
    }

}