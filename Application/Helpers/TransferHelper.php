<?php

namespace Application\Helpers;

use Application\Models\Expression;
use Application\Models\Transfer;
use System\Core\Model;

class TransferHelper
{
    public static function prepare( $transfers )
    {
        $lang = Model::get("\Application\Models\Language", 'brd');

        $orders = [];
        $charityIds = [];
        $userIds = [];
        foreach ($transfers as &$transfer) 
        {
            $orders[$transfer['order_id']] = $transfer['order_id'];

            switch ( $transfer['receiver_type'] ) {
                case Transfer::RECEIVER_ADVISOR:
                    
                    $userIds[$transfer['receiver_id']] = $transfer['receiver_id'];

                    break;
                
                case Transfer::RECEIVER_CHARITY:
                    
                    $charityIds[$transfer['receiver_id']] = $transfer['receiver_id'];

                    break;
            }
        }

         /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getInfoByIds( $orders );

         /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->getByIds( $charityIds );

         /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds( $userIds );

        foreach( $transfers as &$transfer )
        {
            $transfer['order'] = isset($orders[$transfer['order_id']]) ? $orders[$transfer['order_id']] : null;

            switch ( $transfer['receiver_type'] ) {
                case Transfer::RECEIVER_CHARITY:
                    
                    $receiver = [
                        'name' => isset($charities[$transfer['receiver_id']]) ? $charities[$transfer['receiver_id']][ $lang->current() . '_name'] : null
                    ];

                    $transfer['receiver'] = $receiver; 
                    $transfer['transfer_amount'] = $transfer['order']['advisor_amount'];
                    break;

                case Transfer::RECEIVER_ADVISOR:

                    $receiver = [
                        'name' => isset($users[$transfer['receiver_id']]) ? $users[$transfer['receiver_id']]['name'] : null
                    ];
                    $transfer['receiver'] = $receiver; 
                    $transfer['transfer_amount'] = $transfer['order']['advisor_amount'];   
                    break;

                case Transfer::RECEIVER_ADMIN:
                    $transfer['receiver'] = array('name' => $lang('admin'));
                    $transfer['transfer_amount'] = $transfer['order']['admin_amount'];   
                    break;
            }
        }

        return $transfers;
    }
}