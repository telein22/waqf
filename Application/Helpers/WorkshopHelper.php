<?php

namespace Application\Helpers;

use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Config;
use System\Core\Model;

class WorkshopHelper
{
    public static function prepare( $data, $viewerId = null )
    {
        if ( empty($data) ) return $data;
        $viewerId = User::getId($viewerId);

        $ids = array();
        $charities = array();
        $invitees = array();
        $userIds = array();

        foreach ( $data as & $item )
        {
            $ids[$item['id']] = $item['id'];
            $item['charity'] = json_decode($item['charity'], true);
            foreach ( $item['charity'] as $id )
            {
                $charities[$id] = $id;
            }

            if ( $item['invite'] )
            {
                $item['invite'] = str_replace('@', '', $item['invite']);
                $invitees[$item['invite']] = $item['invite'];
            }

            $userIds[$item['user_id']] = $item['user_id'];
        }

        // Current user
        $userM = Model::get('\Application\Models\User');        

        $invitees = $userM->getInfoByUsernames($invitees);

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');
        $counts = $partiM->count($ids, Workshop::ENTITY_TYPE);

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->getByIds($charities);

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');

        $users = $userM->getInfoByIds( $userIds );
        
        foreach ( $data as & $item )
        {
            $item['participant_count'] = isset($counts[$item['id']]) ? $counts[$item['id']] : 0;
            $item['owner'] = $viewerId == $item['user_id'];

            // Add charities
            $chs = [];
            foreach( $item['charity'] as $ch )
            {
                $chs[] = isset($charities[$ch]) ? $charities[$ch] : array();
            }

            $item['charity'] = $chs;

            $item['participated'] = $partiM->isParticipated($viewerId, $item['id'], Workshop::ENTITY_TYPE);

            $orders = $orderM->hasOrdered($viewerId, $item['id'], Workshop::ENTITY_TYPE);
            $item['orderedBefore'] = $orderM->ifOrdered( $item['id'], Workshop::ENTITY_TYPE );
            
            $item['ordered'] = !empty($orders); 
            $item['order_details'] = $orders;

            // Add invite info.
            $item['invite'] = isset($invitees[$item['invite']]) ? $invitees[$item['invite']] : null;

            $item['user'] = isset($users[$item['user_id']]) ? $users[$item['user_id']] : null;
        }

        return $data;
    }

    // public static function isExpired( $date )
    // {
    //     $will = strtotime($date);
    //     $currentTime = time();

    //     $config = Config::get('Website')->service_start_padding;
        
    //     $endPadding = $will + $config[1];

    //     return $currentTime > $endPadding;
    // }
    
    public static function isExpired( $startDate, $duration )
    {
        $will = strtotime($startDate);
        $currentTime = time();
        
        $endPadding = $will + ($duration * 60);

        return $currentTime >= $endPadding;
    }


    public static function orderExpired( $date )
    {
        $will = strtotime($date);
        $currentTime = time();
        
        $endPadding = $will - ( 2 * 60 );

        return $currentTime > $endPadding;
    }
}