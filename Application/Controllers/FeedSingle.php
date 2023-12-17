<?php

namespace Application\Controllers;

use Application\Helpers\FeedHelper;
use Application\Main\MainController;
use Application\Models\Feed;
use Application\Models\HiddenEntities;
use Application\Models\User;
use Application\Models\Settings;
use System\Core\Controller;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class FeedSingle extends MainController
{
    public function index( Request $request, Response $response )
    {
        $userInfo = [];
        if ( $this->user->isLoggedIn() )
        {
            $userInfo = $this->user->getInfo();
        }

        $id = $request->param(0);

        $feedM = Model::get('\Application\Models\Feed');
        $feed = $feedM->getFeed($id);
        if ( empty($feed) ) throw new Error404();

        // Check if the feed is hidden and user is not admin
        if ( !isset($userInfo['type']) || $userInfo['type'] != User::TYPE_ADMIN ) {
            /**
             * @var HiddenEntities
             */
            $hiddenM = Model::get(HiddenEntities::class);
            if ( $hiddenM->isHidden($feed['id'], Feed::ENTITY_TYPE) ) throw new Error404;

            if ( $feed['deleted'] == 1 ) throw new Error404;
        }

        $iFrame = $request->get('iFrame') ? true : false;

        $feed = FeedHelper::prepare([$feed], null);
        $feed = $feed[0];

        /**
         * @var \Application\Models\Settings
         */
        $sM =  Model::get('\Application\Models\Settings');
        $vat = $sM->take(Settings::KEY_VAT, 0);
        $platform_fees = $sM->take(Settings::KEY_PLATFORM_FEES, 0);

        $view = new View();
        $view->set('Feed/single', [
            'userInfo' => $userInfo,
            'feed' => $feed,
            'vat' => $vat,
            'platform_fees' => $platform_fees,
            'iFrame' => $iFrame
        ]);

        if ( $this->user->isLoggedIn() )
        {
            $view->prepend('header', [
                'title' => "Welcome to telein"
            ]);
            $view->append('footer');
        }else {

            $viewContent = $view->content();
            $view = new View();
            $view->set('base', [
                'content' => $viewContent
            ]);
        }

        $response->set($view);
    }

    public function allFeeds( Request $request, Response $response )
    {
        $userInfo = [];
        if ( $this->user->isLoggedIn() )
        {
            $userInfo = $this->user->getInfo();
        }

        //$id = $request->param(0);

        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getAllFeeds();
        if ( empty($feeds) ) throw new Error404();

        $iFrame = $request->get('iFrame') ? true : false;

        $feeds = FeedHelper::prepare([$feeds], null);

        $view = new View();
        $view->set('Feed/all-feeds', [
            'toastArr' => null,
            'userInfo' => null,
            'charities' => null,
            'feeds' => $feeds,
            'feedLimit' => null,
            'trends' => null,
            'iFrame' => $iFrame,
            // 'suggest' => $suggest,
            // 'aWorkshops' => $aWorkshops,
            // 'bWorkshops' => $bWorkshops,
            'aCalls' => null,
            'bCalls' => null,
            'aCons' => null,
            'bCons' => null
        ]);

        if ( $this->user->isLoggedIn() )
        {
            $view->prepend('header', [
                'title' => "Welcome to telein"
            ]);
            $view->append('footer');
        }else {

            $viewContent = $view->content();
            $view = new View();
            $view->set('base', [
                'content' => $viewContent
            ]);
        }

        $response->set($view);
    }


}
