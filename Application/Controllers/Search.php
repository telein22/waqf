<?php

namespace Application\Controllers;

use Application\Controllers\Ajax\SubSpecialty;
use Application\Helpers\FeedHelper;
use Application\Main\MainController;
use Application\Models\Feed;
use Application\Models\HashTags;
use Application\Models\SearchHistory;
use Application\Models\Specialty;
use Application\Models\SubSpecialty as ModelsSubSpecialty;
use Application\Models\UserSubSpecialty;
use System\Core\Controller;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Search extends MainController
{
    public function index( Request $request, Response $response )
    {
        if( $this->user->isLoggedIn() )
        {
            if ( !$this->user->isVerified() ) throw new Redirect("verify-account");
        }

        // $userInfo = $this->user->getInfo();

        $userId = 0;
        
        $q = $request->get('q');
        $spec = $request->get('spec', []);
        $subSpec = $request->get('subSpec', []);
        $userIds = $request->get('users', []);
        $type = $request->param(0);

        $isHash = substr($q, 0, 1) === '#';

        $showUsers = true;
        $showFeeds = true;
        $staticLoadMore = !$isHash;
        switch( $type )
        {
            case 'feeds':
                $showUsers = false;
                $staticLoadMore = false;
                break;
            case 'users':
                $showFeeds = false;
                $staticLoadMore = false;
                break;
            default:
                $type = 'all';
        }

         /**
         * @var Specialty
         */
        $speM = Model::get(Specialty::class);
        $specs = $speM->all();

        $subSpecs = [];
        if ( !empty($spec) )
        {
            /**
            * @var ModelsSubSpecialty
            */
            $subsM = Model::get(ModelsSubSpecialty::class);
            $subSpecs = $subsM->getBySpecialty($spec);
        }

         /**
         * @var Specialty
         */
        $speM = Model::get(Specialty::class);
        $specs = $speM->all();

        $subSpecs = [];
        if ( !empty($spec) )
        {
            /**
            * @var ModelsSubSpecialty
            */
            $subsM = Model::get(ModelsSubSpecialty::class);
            $subSpecs = $subsM->getBySpecialty($spec);
        }

        $searchUsers = [];
        if ( !empty($userIds) )
        {
            $searchUsers = $this->user->getInfoByIds($userIds);
        }

        $limit = 5;

        // User search
        // IF not hash tag search for users
        $users = [];
        if ( !$isHash && $showUsers )
        {
            $users = $this->user->search($q, $subSpec, $userIds, 0, $limit, null, true);
        }

        $feeds = [];
        if ( $showFeeds )
        {
            $userId = 0;
            if ( $this->user->isLoggedIn() )
            {
                $userInfo = $this->user->getInfo();
                $userId = $userInfo['id'];
            }

            /**
             * @var Feed
             */
            $feedM = Model::get(Feed::class);
            $feeds = $feedM->search($q, $subSpec, $userIds, null, $limit, true);
            $feeds = FeedHelper::prepare($feeds, $userId);
        }

        if ( !empty($q) )
        {
            /**
             * @var SearchHistory
             */
            $searchM = Model::get(SearchHistory::class);
            $searchM->create([
                'user_id' => $userId,
                'term' => $q,
                'created_at' => time()
            ]);
        }
       

        // Feed search3
        $view = new View();
        $view->set('Search/index', [
            'isHash' => $isHash,
            'q' => $q,
            'users' => $users,
            'feeds' => $feeds,
            'specs' => $specs,
            'spec' => $spec,
            'subSpecs' => $subSpecs,
            'subSpec' => $subSpec,
            'limit' => $limit,
            'type' => $type,
            'staticLoadMore' => $staticLoadMore,
            'showUsers' => $showUsers,
            'showFeeds' => $showFeeds,
            'searchUsers' => $searchUsers,
            // 'userInfo' => $userInfo
        ]);

        if ( !$this->user->isLoggedIn() )
        {
            $view->append('Outer/footer');
            $view->prepend('Outer/header');
        } else {
            $view->append('footer');
            $view->prepend('header');
        }

        $response->set($view);
    }
}