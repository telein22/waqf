<?php

namespace Application\Helpers;

use System\Core\Model;

class LogHelper
{
    public static function prepare( $logs )
    {
        $userM = Model::get('\Application\Models\User');
        $userIds = array();        

        foreach ($logs as $log) {
            $userIds[$log['user_id']] = $log['user_id'];
        }

        $users = $userM->getInfoByIds( $userIds );

        foreach ($logs as &$log) {

            $log['user'] = isset($users[$log['user_id']]) ? $users[$log['user_id']] : null;
        }

        return $logs;
    }
}