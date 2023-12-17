<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\FeedHelper;
use Application\Helpers\HashTagHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed as FeedM;
use Application\Models\Meeting;
use Application\Models\MeetingApi;
use Application\Models\Workshop;
use Error;
use System\Core\Config;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\JSON;
use System\Responses\View;

class OuterFeed extends Controller
{

    public function moreMedia( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getMediaFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, 0);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            $view = new View();
            $view->set('Feed/static_feed', [
                
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }

    public function moreComment( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getCommentedFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, 0);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            $view = new View();
            $view->set('Feed/static_feed', [
                
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }

    public function moreLiked( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getLikedFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, 0);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            $view = new View();
            $view->set('Feed/static_feed', [
                
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }
    
    public function moreProfile( Request $request, Response $response )
    {
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;       

        $profileId = $request->post('profileId');

        

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getProfileFeeds($profileId, $fromId ,$limit);
        $feeds = FeedHelper::prepare($feeds, 0);

        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            $view = new View();
            $view->set('Feed/static_feed', [
                
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }
}