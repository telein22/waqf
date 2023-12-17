<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Helpers\BlockedFeedHelper;
use Application\Helpers\FeedHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Responses\JSON;

class DataTable extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $data = array();
        $userId = $request->post('userId');
        $type = $request->param(0);
        $userInfo = $this->user->getInfo();
        $hiddenFeeds = [];
        
        if ($type == 'feeds') 
        {
            $data = array();

            // $orderIndex = $request->get('order')[0]['column'];
            // $orderBy = strtoupper($request->get('order')[0]['dir']);
            // $column = $request->get('columns')[$orderIndex]['data'];

            $search = $request->get('search')['value'];
            $start = $request->get('start');
            $length = $request->get('length');

            $from = $request->get('from');
            $to = $request->get('to');

            $feedM = Model::get('\Application\Models\Feed');
            $feeds = $feedM->all(null, $start, $length, $from, $to, $search);
            $feeds = FeedHelper::prepare($feeds, null);

            $hiddenM = Model::get('\Application\Models\HiddenEntities');
            $entities = $hiddenM->listByType( Feed::ENTITY_TYPE );

            foreach( $entities as $item )
            {
                $hiddenFeeds[] = $item['entity_id'];
            }

            foreach( $feeds as $feed )
            {
                $tempData = array();

                $tempData['id'] = htmlentities($feed['id']);
                $tempData['username'] = htmlentities($feed['owner_name']);
                $tempData['email'] =  htmlentities($feed['owner_email']);
                $tempData['text'] =  htmlentities($feed['text']);
                $tempData['status'] = $feed['deleted'] == 0 ? 'Active' : 'Deleted';
                $tempData['created_at'] = date('d-m-Y H:i', $feed['created_at']);
                $str = '<button type="button" data-id="'. $feed['id'] .'" class="btn view-feed"><i class="fas fa-eye"></i></button>';
                if(  in_array( $feed['id'], $hiddenFeeds ) )
                {
                    $str .= '<button type="button" data-id="'. $feed['id'] .'" class="btn feed-show">Unhide</button>';
                } else 
                {
                    $str .= '<button type="button" data-id="'. $feed['id'] .'" class="btn feed-hide">Hide</button>';
                }
                $tempData['action'] = $str;

                $data[] = $tempData;
            }
            
            $feeds = $feedM->all(null, null, null, $from, $to, $search);
    
            $json_data = array(
                "draw"            => intval($request->get('draw')),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval(count($feeds)), // total number of records
                "recordsFiltered" => intval(count($feeds)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
            );
    
    
            $json = new JSON();
            $json->set($json_data);
            $response->set($json);
        }

        if ($type == 'feeds-with-blocked') 
        {
            $data = array();

            // $orderIndex = $request->get('order')[0]['column'];
            // $orderBy = strtoupper($request->get('order')[0]['dir']);
            // $column = $request->get('columns')[$orderIndex]['data'];

            $search = $request->get('search')['value'];
            $start = $request->get('start');
            $length = $request->get('length');

            $from = $request->get('from');
            $to = $request->get('to');

            $blockedFeedM = Model::get('\Application\Models\BlockedFeedWords');
            $feeds = $blockedFeedM->all(null, $start, $length, $from, $to, $search);
            $feeds = BlockedFeedHelper::prepare($feeds, null);
            $feeds = FeedHelper::prepare($feeds, null);

            foreach( $feeds as $feed )
            {
                $tempData = array();

                $tempData['username'] = htmlentities($feed['owner_name']);
                $tempData['email'] =  htmlentities($feed['owner_email']);
                $tempData['text'] =  htmlentities($feed['text']);
                $tempData['status'] = $feed['deleted'] == 0 ? 'Active' : 'Deleted';
                $tempData['word'] =  htmlentities($feed['word']);
                $tempData['action'] = '
                <button type="button" data-id="'. $feed['id'] .'" class="btn view-feed"><i class="fas fa-eye"></i></button>
                ';
                if( $feed['hidden'] )
                {
                    $tempData['action'] .= '<button type="button" data-id="'. $feed['id'] .'" class="btn feed-show">Show</button>';
                } else 
                {
                    $tempData['action'] .= '<button type="button" data-id="'. $feed['id'] .'" class="btn feed-hide">Hide</button>';
                }

                $data[] = $tempData;
            }

            $feeds = $blockedFeedM->all(null, null, null, $from, $to, $search);
    
            $json_data = array(
                "draw"            => intval($request->get('draw')),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
                "recordsTotal"    => intval(count($feeds)), // total number of records
                "recordsFiltered" => intval(count($feeds)), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $data   // total data array
            );
    
    
            $json = new JSON();
            $json->set($json_data);
            $response->set($json);
        }

    }
}
