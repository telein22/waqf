<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\CacheHelper;
use Application\Helpers\FollowerHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Models\Notification as ModelsNotification;

class Follow extends AuthController
{
    public function toggle( Request $request, Response $response )
    {
        $followId = $request->post('id');

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');

        if ( $followM->isFollowing($userInfo['id'], $followId) )
        {
            $followM->unFollow($userInfo['id'], $followId);
        } else {
            $followM->follow($userInfo['id'], $followId);

            $json = json_encode(array( 'follow_id' => $userInfo['id'] ));
    
            $data = array(
                'sender_id' => $userInfo['id'],
                'receiver_id' => $followId,
                'type' => 'social',
                'action_type' => ModelsNotification::ACTION_FOLLOW,
                'read' => 0,
                'sent' => 0,
                'data' => $json,
                'created_at' => time()
            );
    
            $this->hooks->dispatch('feed.on_follow', $data)->later();

            CacheHelper::forget(CacheHelper::USER_PROFILE_KEY, $followId);
        }

        throw new ResponseJSON('success');
    }

    public function moreFollower( Request $request )
    {
        $skip = $request->post('skip');
        $userId = $request->post('userId');

        $userInfo = $this->user->getInfo();

        if ( empty($userId) ) $userId = $userInfo['id'];

        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followers = $followM->getFollowers($userId, $skip, FollowerHelper::PAGE_LIMIT, true);
        $followers = FollowerHelper::prepare($followers);

        $dataAvl = FollowerHelper::PAGE_LIMIT == count($followers);

        $output = array();
        foreach ( $followers as $follow )
        {
            $view = new View();
            $view->set('Profile/user_profile', [                
                'user' => $follow['follower']
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'followers' => $output,
            'dataAvl' => $dataAvl,
            'skip' => $skip + FollowerHelper::PAGE_LIMIT
        ));
    }

    public function moreFollowing( Request $request )
    {
        $skip = $request->post('skip');
        $userId = $request->post('userId');

        $userInfo = $this->user->getInfo();

        if ( empty($userId) ) $userId = $userInfo['id'];

        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');
        $followings = $followM->getFollowing($userId, $skip, FollowerHelper::PAGE_LIMIT, true);
        $followings = FollowerHelper::prepare($followings);

        $dataAvl = FollowerHelper::PAGE_LIMIT == count($followings);

        $output = array();
        foreach ( $followings as $follow )
        {
            $view = new View();
            $view->set('Profile/user_profile', [                
                'user' => $follow['follow']
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'followings' => $output,
            'dataAvl' => $dataAvl,
            'skip' => $skip + FollowerHelper::PAGE_LIMIT
        ));
    }
}