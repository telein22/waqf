<?php

namespace Application\Helpers;

use System\Core\Model;

class ParticipantHelper
{
    public static function prepare( $data )
    {
        if ( empty($data) ) return $data;

        $uIds = array();
        foreach ( $data as $item )
        {
            $uIds[$item['user_id']] = $item['user_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($uIds);

        

        foreach ( $data as & $item )
        {
            $item['user'] = isset($users[$item['user_id']]) ? $users[$item['user_id']] : null;
            if ( $item['user'] ) $item['user']['avatar'] = UserHelper::getAvatarUrl('fit:300,300', $item['user']['id']);
            $item['participated_at'] = DateHelper::butify($item['participated_at']);

            unset($item['user_id']);
        }

        return $data;

    }

    public static function prepareByEntity( $data )
    {
        if ( empty($data) ) return $data;

        $uIds = array();
        foreach ( $data as $item )
        {
            $uIds[$item['user_id']] = $item['user_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($uIds);

        $output = [];
        foreach ( $data as $item )
        {
            $output[$item['entity_type']][$item['entity_id']][] = isset($users[$item['user_id']]) ? $users[$item['user_id']] : null;
        }

        return $output;
    }
}