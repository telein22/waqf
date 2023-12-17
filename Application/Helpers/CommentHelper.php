<?php

namespace Application\Helpers;

use System\Core\Model;

class CommentHelper
{
    public static function prepare( $comments )
    {
        if ( empty($comments) )  return $comments;

        $userIds = array();

        foreach ( $comments as $comment )
        {
            $userIds[$comment['user_id']] = $comment['user_id'];
        }

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        $output = array();
        foreach ( $comments as $i => $comment )
        {
            if ( !isset( $output[$comment['entity_type']]) )  $output[$comment['entity_type']] = [];
            $output[$comment['entity_type']][$comment['entity_id']][$i] = $comment;
            $output[$comment['entity_type']][$comment['entity_id']][$i]['user'] = isset($users[$comment['user_id']]) ? $users[$comment['user_id']] : null;
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
}