<?php

namespace Application\Helpers;

use System\Core\Model;

class ExpressionHelper
{
    public static function prepare( $expressions )
    {
        if ( empty($expressions) )  return $expressions;

        $userIds = array();

        foreach ( $expressions as $expression )
        {
            $userIds[$expression['user_id']] = $expression['user_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        $output = array();
        foreach ( $expressions as $i => $expression )
        {
            if ( !isset( $output[$expression['entity_type']]) )  $output[$expression['entity_type']] = [];
            $output[$expression['entity_type']][$expression['entity_id']][$i] = $expression;
            $output[$expression['entity_type']][$expression['entity_id']][$i]['user'] = isset($users[$expression['user_id']]) ? $users[$expression['user_id']] : null;
        }

        return $output;

    }

    public static function prepareCount( $data )
    {
        if ( empty($data) )  return $data;

        
        $output = array();
        foreach ( $data as $i => $value )
        {
            if ( !isset( $output[$value['entity_type']]) )  $output[$value['entity_type']] = [];
            $output[$value['entity_type']][$value['entity_id']] = (int) $value['total'];
        }

        return $output;
    }

    public static function prepareIsExpressed( $data )
    {
        if ( empty($data) )  return $data;
        
        $output = array();
        foreach ( $data as $i => $value )
        {
            if ( !isset( $output[$value['entity_type']]) )  $output[$value['entity_type']] = [];
            $output[$value['entity_type']][$value['entity_id']] = true;
        }

        return $output;
    }
}