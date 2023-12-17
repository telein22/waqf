<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\FeedHelper;
use Application\Main\ResponseJSON;
use Application\Models\Feed;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Search extends Controller
{
    public function index( Request $request, Response $response )
    {
        $q = $request->post('q');
        $spec = $request->post('spec', []);
        $subSpec = $request->post('subSpec', []);
        $type = $request->post('type', 'feeds');
        $fromId = $request->post('fromId');

        $limit = 10;
        $output = [
            'data' => [],
            'dataAvl' => false,
            'lastId' => 0,
        ];

        switch( $type )
        {
            case 'feeds':   
                $userId = 0;
                if ( $this->user->isLoggedIn() )
                {
                    $userInfo = $this->user->getInfo();
                    $userId = $userInfo['id'];
                }
                
                $feedM = Model::get(Feed::class);
                $feeds = $feedM->search($q, $subSpec, $fromId - 1, $limit);
                $feeds = FeedHelper::prepare($feeds, $userId);

                foreach ( $feeds as $feed )
                {
                    $view = new View();
                    $view->set('Search/Parts/feed', [
                        'feed' => $feed
                    ]);
                    $output['data'][] = $view->content();
                    $output['lastId'] = $feed['id'];
                }

                $output['dataAvl'] = count($output['data']) == $limit;             
                break;
            case 'users':
                $users = $this->user->search($q, $subSpec, $fromId, $limit);

                foreach ( $users as $user )
                {
                    $view = new View();
                    $view->set('Search/Parts/user', [ 'user' => $user ]);
                    $output['data'][] = $view->content();                    
                }

                $output['dataAvl'] = count($output['data']) == $limit;
                $output['lastId'] = $fromId + $limit;
                break;
            default:
                throw new ResponseJSON('error', "Invalid type");
        }

        throw new ResponseJSON('success', $output);

    }
}