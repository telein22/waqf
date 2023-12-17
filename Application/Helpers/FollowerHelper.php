<?php

namespace Application\Helpers;

use System\Core\Model;

class FollowerHelper
{
    const PAGE_LIMIT = 12;

    public static function prepare( $data )
    {
        if ( empty($data) ) return $data;

        $followers = array();
        $follow = array();
        foreach ( $data as $item )
        {
            $followers[$item['follower']] = $item['follower'];
            $follow[$item['follow']] = $item['follow'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $followers = $userM->getInfoByIds($followers);
        $follow = $userM->getInfoByIds($follow);

        foreach ( $data as & $item )
        {
            $item['follow'] = isset($follow[$item['follow']]) ? $follow[$item['follow']] : null;
            $item['follower'] = isset($followers[$item['follower']]) ? $followers[$item['follower']] : null;
        }

        return $data;
    }
}