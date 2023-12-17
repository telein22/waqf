<?php

namespace Application\Helpers;

use Application\Models\ServiceLog;
use Application\Models\User;
use System\Core\Model;

class ServiceLogHelper 
{
    public static function prepare( $data )
    {
        if ( empty($data) ) return $data;

        $userIds = [];
        foreach ( $data as $item )
        {
            if ( $item['type'] == ServiceLog::TYPE_USER ) $userIds[$item['action_by']] = $item['action_by'];
        }

        /**
         * @var User
         */
        $userM = Model::get(User::class);
        $users = $userM->getInfoByIds($userIds);

        foreach ( $data as & $item )
        {
            if (
                $item['type'] == ServiceLog::TYPE_USER &&
                isset($users[$item['action_by']])
            ) $item['action_by'] = $users[$item['action_by']];
        }

        return $data;
    }
}