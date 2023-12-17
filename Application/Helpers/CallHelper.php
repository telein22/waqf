<?php

namespace Application\Helpers;

use System\Core\Model;

class CallHelper
{
    public static function prepare( $data, ?int $userId = null)
    {
        if ( empty($data) ) return $data;

        $charities = array();
        $userIds = array();

        foreach ( $data as & $item )
        {
            $item['charity'] = json_decode($item['charity'], true);
            foreach ( $item['charity'] as $id )
            {
                $charities[$id] = $id;
            }

            $userIds[$item['owner_id']] = $item['owner_id'];
            $userIds[$item['created_by']] = $item['created_by'];
        }

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->getByIds($charities);

        // Fetch all users
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        foreach ( $data as & $item )
        {
            $chs = [];
            foreach ( $item['charity'] as $ch )
            {
                $chs[] = $charities[$ch] ?? array();
            }

            $item['charity'] = $chs;
            
            $item['from'] = $users[$item['owner_id']] ?? null;
            unset($item['owner_id']);
            $item['for'] = $users[$item['created_by']] ?? null;
            unset($item['created_by']);

            if ($userId) {
                $item['its_mine'] = $userId == $item['from']['id'];
            }
        }

        return $data;

    }

    public static function prepareCalender( $data )
    {
        if ( empty($data) ) return $data;

        $charities = array();
        foreach ( $data as & $item )
        {
            $item['charity'] = json_decode($item['charity'], true);
            foreach ( $item['charity'] as $id )
            {
                $charities[$id] = $id;
            }
        }

         /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->getByIds($charities);

        $output = [];
        $i = 0;
        foreach ( $data as & $item )
        {
            $chs = [];
            foreach ( $item['charity'] as $ch )
            {
                $chs[] = isset($charities[$ch]) ? $charities[$ch] : array();
            }

            $item['charity'] = $chs;

            $output[$item['date']][] = $item;
        }

        return $output;
    }

    public static function prepareSlots( $data )
    {
        if ( empty($data) ) return $data;

        $charities = array();
        $userIds = array();
        $slotIds = array();

        foreach ( $data as & $item )
        {
            $item['charity'] = json_decode($item['charity'], true);
            $slotIds[$item['id']] = $item['id'];
            $userIds[$item['user_id']] = $item['user_id'];

            foreach ( $item['charity'] as $id )
            {
                $charities[$id] = $id;
            }
        }

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->getByIds($charities);

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);
        /**
         * @var \Application\Models\CallSlot
         */
        $callSM = Model::get('\Application\Models\CallSlot');
        $bookings = $callSM->getSlotBookingBySlotIds($slotIds);

        $i = 0;
        foreach ( $data as & $item )
        {
            $chs = [];
            foreach ( $item['charity'] as $ch )
            {
                $chs[] = isset($charities[$ch]) ? $charities[$ch] : array();
            }

            $item['charity'] = $chs;
            $item['user'] = isset($users[$item['user_id']]) ? $users[$item['user_id']] : null;
            $item['booking'] = isset($bookings[$item['id']]) ? count($bookings[$item['id']]) : 0;
            $item['call'] = isset($bookings[$item['id']]) ? $bookings[$item['id']][0] : null;
        }

        return $data;
    }
}