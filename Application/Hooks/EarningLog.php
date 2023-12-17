<?php

namespace Application\Hooks;

use Application\Models\EarningLog as ModelsEarningLog;
use Application\Models\Order;
use Application\Models\User;
use System\Core\Model;

class EarningLog
{
    public function orderAccept( $order )
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $arr = array(
            'text' => 'order_successfully_Created_log'
        );
        
        $data = array(
            'entity_id' => $order['id'],
            'entity_type' => Order::ENTITY_TYPE,
            'action_type' => ModelsEarningLog::LOG_ORDER_ACCEPT,
            'data' => json_encode($arr),
            'created_at' => time(),
            'user_id' => $userInfo['id']
        );
        
        /**
         * @var \Application\Models\EarningLog
         */
        $logM = Model::get('\Application\Models\EarningLog');
        $logM->create( $data );
    }

    public function orderCancel( $order )
    {
        $userM = Model::get("\Application\Models\User");

        $arr = array(
            'text' => 'order_declined_log'
        );

        $data = array(
            'entity_id' => $order['id'],
            'entity_type' => Order::ENTITY_TYPE,
            'action_type' => ModelsEarningLog::LOG_ORDER_DECLINE,
            'data' => json_encode($arr),
            'created_at' => time(),
            'user_id' => $order['entity_owner_id']
        );

        /**
         * @var \Application\Models\EarningLog
         */
        $logM = Model::get('\Application\Models\EarningLog');
        $logM->create( $data );
    }

    public function orderSuccess( $order )
    {
        $arr = array(
            'text' => 'order_pending_log'
        );
        
        $data = array(
            'entity_id' => $order['id'],
            'entity_type' => Order::ENTITY_TYPE,
            'action_type' => ModelsEarningLog::LOG_ORDER_PENDING,
            'data' => json_encode($arr),
            'created_at' => time(),
            'user_id' => $order['entity_owner_id']
        );
        
        /**
         * @var \Application\Models\EarningLog
         */
        $logM = Model::get('\Application\Models\EarningLog');
        $result = $logM->create( $data );
    }
}