<?php

namespace Application\Controllers;

use Application\Helpers\CallHelper;
use Application\Helpers\ConversationHelper;
use Application\Helpers\FeedHelper;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\HashTags;
use Application\Models\Specialty;
use Application\Models\SubSpecialty;
use Application\Models\UserSubSpecialty;
use Application\Models\Workshop;
use System\Core\Controller;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Feed extends AuthController
{
    public function index( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $feedLimit = 4;

        $toastArr = array();
        if( $request->get('errors') )
        {
            $toastArr = json_decode($request->get('errors'), true);
        }

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getFeeds($userInfo['id'], null, $feedLimit);
        // var_dump($feeds);exit;
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

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();

        $trends = $this->_prepareTrending();

        // $suggest = [];
        // if ( count($feeds) <= 3000 )
        // {
            
        //     $suggest = $this->user->search('', null, null, 0, 10, $userInfo['id']);
        // }

        // Upcoming workshop
        // /**
        //  * @var Workshop
        //  */
        // $workM = Model::get(Workshop::class);
        // $aWorkshops = $workM->findUpcoming($userInfo['id'], 5);        
        // $bWorkshops = $workM->findUpcoming($userInfo['id'], 5, false);

         /**
         * @var Call
         */
        $callM = Model::get(Call::class);
        $aCalls = $callM->findUpcoming($userInfo['id'], 5);        
        $aCalls = CallHelper::prepare($aCalls, null);
        $bCalls = $callM->findUpcoming($userInfo['id'], 5, false);
        $bCalls = CallHelper::prepare($bCalls, null);

        /**
         * @var Conversation
         */
        $conM = Model::get(Conversation::class);
        $aCons = $conM->findUpcoming($userInfo['id'], 5);        
        $aCons = ConversationHelper::prepare($aCons);
        $bCons = $conM->findUpcoming($userInfo['id'], 5, false);
        $bCons = ConversationHelper::prepare($bCons);      
        

        $view = new View();
        $view->set('Feed/index', [
            'toastArr' => $toastArr,
            'userInfo' => $userInfo,
            'charities' => $charities,
            'feeds' => $feeds,
            'feedLimit' => $feedLimit,
            'trends' => $trends,
            // 'suggest' => $suggest,
            // 'aWorkshops' => $aWorkshops,
            // 'bWorkshops' => $bWorkshops,
            'aCalls' => $aCalls,
            'bCalls' => $bCalls,
            'aCons' => $aCons,
            'bCons' => $bCons
        ]);

        $view->prepend('header', [
            'title' => "Feed"
        ]);
        $view->append('footer');

        $response->set($view);
    }

    private function _prepareTrending()
    {
        /**
         * @var HashTags
         */
        $hashTags = Model::get(HashTags::class);
        $hashTags = $hashTags->fetchMostUsed(10);

        /**
         * @var UserSubSpecialty
         */
        $subM = Model::get(UserSubSpecialty::class);
        $subSpecs = $subM->getTrending(10);

        $ids  = [];
        foreach ( $subSpecs as $specs )
        {
            $ids[] = $specs['special_id'];
        }

        /**
         * @var Specialty
         */
        $specM = Model::get(Specialty::class);
        $specs = $specM->getByIdList($ids);

        foreach ( $subSpecs as & $s )
        {
            $s['parent'] = isset($specs[$s['special_id']]) ? $specs[$s['special_id']] : null;
        }

        return [
            'hash' => $hashTags,
            'specialty' => $subSpecs
        ];
    }
}
