<?php

namespace Application\Controllers;

use Application\Helpers\FeedHelper;
use Application\Main\MainController;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class LikedFeeds extends MainController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $feedLimit = 4;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getLikedFeeds($userInfo['id'], null, $feedLimit, true);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
        foreach( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }
        }

        $view = new View();
        $view->set('LikedFeeds/index', [
            'userInfo' => $userInfo,
            'feeds' => $feeds,
            'feedLimit' => $feedLimit
        ]);
        $view->prepend('header', [
            'title' => "Liked tweets"
        ]);
        $view->append('footer');

        $response->set($view);
    }
}