<?php

namespace Application\Helpers;

use Application\Models\Expression;
use System\Core\Model;

class FeedHelper
{

    // public static function prepareOuter( $feeds )
    // {

    //     if ( empty($feeds) )  return $feeds;

    //     // first get the list of owner ids
    //     // then merge the comments
    //     $ownerIds = [];
    //     $workshopIds = [];
    //     $feedIds = [
    //         'feed' => []
    //     ];

    //     foreach ( $feeds as $feed )
    //     {
    //         $ownerIds[$feed['user_id']] = $feed['user_id'];
    //         $data = json_decode($feed['data'], true);

    //         if ( isset($data['workshop']) )
    //         {
    //             $workshopIds[$data['workshop']] = $data['workshop'];
    //         }

    //         $feedIds['feed'][] = $feed['id'];
    //     }

    //     // var_dump($feedIds);exit;

    //     /**
    //      * @var \Application\Models\Comment
    //      */
    //     $commentM = Model::get('\Application\Models\Comment');
    //     $comments = $commentM->getComments($feedIds);
    //     $comments = CommentHelper::prepare($comments);
    //     $countComments = $commentM->countComment($feedIds);
    //     $countComments = CommentHelper::prepareCount($countComments);        

    //     /**
    //      * @var \Application\Models\Expression
    //      */
    //     $expressM = Model::get('\Application\Models\Expression');
    //     $countLikes = $expressM->countExpression($feedIds, Expression::LIKE);
    //     $countLikes = ExpressionHelper::prepareCount($countLikes);     


    //     /**
    //      * @var \Application\Models\User
    //      */
    //     $userM = Model::get('\Application\Models\User');
    //     $users = $userM->getInfoByIds($ownerIds);

    //     /**
    //      * @var \Application\Models\Workshop
    //      */
    //     $workM = Model::get('\Application\Models\Workshop');
    //     $workshops = $workM->getInfoByIds($workshopIds);
    //     $workshops = WorkshopHelper::prepare($workshops);        

        
    //     foreach ( $feeds as & $feed )
    //     {
    //         $feedViewerM = Model::get('\Application\Models\FeedViewers');

    //         $feed['totalViews'] = $feedViewerM->count( $feed['id'] ) ? $feedViewerM->count( $feed['id'] )['count'] : 0;

    //         $feed['user'] = isset($users[$feed['user_id']]) ? $users[$feed['user_id']] : null;
    //         $feed['data'] = json_decode($feed['data'], true);

    //         if ( isset($feed['data']['workshop']) && isset($workshops[$feed['data']['workshop']]) )
    //         {
    //             $feed['data']['workshop'] = $workshops[$feed['data']['workshop']];
    //         }

    //         $feed['comments'] = isset($comments['feed']) && isset($comments['feed'][$feed['id']]) ?
    //             $comments['feed'][$feed['id']] : [];

    //         $feed['totalComments'] = isset($countComments['feed']) && isset($countComments['feed'][$feed['id']]) ?
    //             $countComments['feed'][$feed['id']] : 0;

    //         $feed['totalExpressions'] = [
    //             'likes' => isset($countLikes['feed']) && isset($countLikes['feed'][$feed['id']]) ? $countLikes['feed'][$feed['id']] : 0
    //         ];

    //         $feed['isExpressed'] = [
    //             'likes' => isset($isLikes['feed']) && isset($isLikes['feed'][$feed['id']]) ? $isLikes['feed'][$feed['id']] : false
    //         ];

    //     }

    //     return $feeds;
        
    // }

    public static function prepare( $feeds, $viewerId )
    {
        if ( empty($feeds) )  return $feeds;

        // first get the list of owner ids
        // then merge the comments
        $ownerIds = [];
        $workshopIds = [];
        $feedIds = [
            'feed' => []
        ];

        foreach ( $feeds as $feed )
        {
            $ownerIds[$feed['user_id']] = $feed['user_id'];
            $data = json_decode($feed['data'], true);

            if ( isset($data['workshop']) && $data['workshop'] != 'deleted')
            {
                $workshopIds[$data['workshop']] = $data['workshop'];
            }

            $feedIds['feed'][] = $feed['id'];
        }

        // var_dump($feedIds);exit;

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $comments = $commentM->getComments($feedIds);
        $comments = CommentHelper::prepare($comments);
        $countComments = $commentM->countComment($feedIds);
        $countComments = CommentHelper::prepareCount($countComments);        

        /**
         * @var \Application\Models\Expression
         */
        $expressM = Model::get('\Application\Models\Expression');
        $countLikes = $expressM->countExpression($feedIds, Expression::LIKE);
        $countLikes = ExpressionHelper::prepareCount($countLikes);
        $isLikes = $expressM->isExpressed($viewerId, $feedIds, Expression::LIKE);
        $isLikes = ExpressionHelper::prepareIsExpressed($isLikes);        


        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($ownerIds);

        /**
         * @var \Application\Models\Workshop
         */
        $workM = Model::get('\Application\Models\Workshop');
        $workshops = $workM->getInfoByIds($workshopIds);
        $workshops = WorkshopHelper::prepare($workshops, $viewerId);        

        
        foreach ( $feeds as & $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            $feed['totalViews'] = $feedViewerM->count( $feed['id'] ) ? $feedViewerM->count( $feed['id'] )['count'] : 0;

            $feed['user'] = isset($users[$feed['user_id']]) ? $users[$feed['user_id']] : null;
            $feed['data'] = json_decode($feed['data'], true);

            if ( isset($feed['data']['workshop']) && $feed['data']['workshop'] != 'deleted' && isset($workshops[$feed['data']['workshop']]) )
            {
                $feed['data']['workshop'] = $workshops[$feed['data']['workshop']];
            }

            $feed['comments'] = isset($comments['feed']) && isset($comments['feed'][$feed['id']]) ?
                $comments['feed'][$feed['id']] : [];

            $feed['totalComments'] = isset($countComments['feed']) && isset($countComments['feed'][$feed['id']]) ?
                $countComments['feed'][$feed['id']] : 0;

            $feed['totalExpressions'] = [
                'likes' => isset($countLikes['feed']) && isset($countLikes['feed'][$feed['id']]) ? $countLikes['feed'][$feed['id']] : 0
            ];

            $feed['isExpressed'] = [
                'likes' => isset($isLikes['feed']) && isset($isLikes['feed'][$feed['id']]) ? $isLikes['feed'][$feed['id']] : false
            ];

        }

        return $feeds;
    }
}