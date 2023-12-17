<?php

namespace Application\Helpers;

use Application\Models\EarningLog;
use Application\Models\Expression;
use Application\Models\log;
use Application\Models\Notification as ModelsNotification;
use Application\Models\Order;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

class EarningLogHelper
{
    public static function prepare( $logs )
    {
        $lang = Model::get('\Application\Models\Language');
        $userIds = array();        

        foreach ($logs as $log) {
            $userIds[$log['user_id']] = $log['user_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        $orderM = Model::get('\Application\Models\Order');

        foreach ($logs as &$log) 
        {
            $entityType = '';
            $log['user'] = isset($users[$log['user_id']]) ? $users[$log['user_id']] : null;

            switch ( $log['entity_type'] ) 
            {
                case Order::ENTITY_TYPE:
                    $entityInfo = $orderM->take( $log['entity_id'] );
                    $userInfo = $userM->getUser( $entityInfo['user_id'] );
                    $entityType = $entityInfo['entity_type'];

                    break;
            }

            $log['entityInfo'] = $entityInfo;

            switch ( $log['action_type'] ) 
            {
                case EarningLog::LOG_ORDER_ACCEPT:
                    
                    $log['full_msg'] = $lang('earning_log_order_accepted', [
                        'entity_type' => $lang( $entityType ),
                        'link' => URL::full('order/view/' . $log['entity_id']),
                        'name' => $userInfo['name'],
                        'amount' => $entityInfo['amount']
                    ]);

                    break;

                case EarningLog::LOG_ORDER_PENDING:
                    
                    $log['full_msg'] = $lang('earning_log_order_pending', [
                        'entity_type' => $lang( $entityType ),
                        'link' => URL::full('order/view/' . $log['entity_id']),
                        'userLink' => '<a target="_blank" href="'. URL::full('profile/' . $userInfo['id']) .'">'. $userInfo['name'] .'</a>'
                    ]);

                    break;

                case EarningLog::LOG_ORDER_DECLINE:
                    
                    $log['full_msg'] = $lang('earning_log_order_declined', [
                        'entity_type' => $lang( $entityType ),
                        'link' => URL::full('order/view/' . $log['entity_id']),
                        'amount' => $entityInfo['amount']
                    ]);

                    break;

                case EarningLog::LOG_ORDER_CANCEL:
                    
                    $log['full_msg'] = $lang('earning_log_order_canceled', [
                        'entity_type' => $lang( $entityType ),
                        'link' => URL::full('order/view/' . $log['entity_id']),
                        'amount' => $entityInfo['amount']
                    ]);

                    break;

                case EarningLog::LOG_ORDER_COMPLETE:
                    
                    $log['full_msg'] = $lang('earning_log_order_completed', [
                        'entity_type' => $lang( $entityType ),
                        'link' => URL::full('order/view/' . $log['entity_id']),
                        'amount' => $entityInfo['amount']
                    ]);

                    break;
            }

        }

        return $logs;
    }
}