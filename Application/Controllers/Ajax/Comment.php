<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\CommentHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Models\Notification as ModelsNotification;

class Comment extends AuthController
{
    public function post( Request $request, Response $response )
    {
        $comment = $request->post('comment');
        $feedId = $request->post('feedId');

        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        if ( empty($comment) ) throw new ResponseJSON('error', [
            $lang('error'),
            $lang('comment_box_empty')
        ]);

        if ( mb_strlen($comment) > 160 ) throw new ResponseJSON('error', [
            $lang('error'),
            $lang('comment_box_max_limit', ['max' => 160])
        ]);

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $result = $commentM->create(array(
            'user_id' => $userInfo['id'],
            'entity_id' => $feedId,
            'entity_type' => Feed::ENTITY_TYPE,
            'comment' => json_encode(['text' => $comment]),
            'created_at' => time()
        ));
        
        if ( !$result ) throw new ResponseJSON('error', ["Error", "Internal server error."]);

        $feedM = Model::get('\Application\Models\Feed');
        $feedInfo = $feedM->getFeed( $feedId );

        $json = json_encode(array( 
            'feed_id' => $feedId,
            'comment_id' => $result,
            'text' => mb_substr($comment, 0 , 100)
        ));

        $data = array(
            'sender_id' => $userInfo['id'],
            'receiver_id' => $feedInfo['user_id'],
            'type' => 'social',
            'action_type' => ModelsNotification::ACTION_FEED_COMMENT,
            'read' => 0,
            'sent' => 0,
            'data' => $json,
            'created_at' => time()
        );

        if( $feedInfo['user_id'] != $userInfo['id'] )
        {
            $this->hooks->dispatch('feed.on_comment', $data)->later();
        }



        throw new ResponseJSON('success', $result);
    }

    public function getComment( Request $request, Response $response )
    {
        $id =  $request->post('id');
        $feedId = $request->post('feedId');

        if ( !$id ) throw new ResponseJSON('error', "Invalid arguments");

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $result = $commentM->getComment($id);

        if ( !$result ) throw new ResponseJSON('error', "Invalid arguments");

        $result = CommentHelper::prepare([$result]);            
        $result = $result['feed'][$feedId][0];

        $view = new View();
        $view->set('Feed/comment', [
            'userInfo' => $this->user->getInfo(),
            'comment' => $result
        ]);

        throw new ResponseJSON('success', array(
            'id' => $result['id'],
            'comment' => $view->content()
        ));
    }

    public function delete( Request $request, Response $response )
    {
        $id = $request->post('id');

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $commentM->delete($id);


        throw new ResponseJSON('success');

    }

    public function load( Request $request, Response $response ) 
    {
        $lastId = $request->post('lastId');
        $feedId = $request->post('feedId');

        $limit = 5;

        /**
         * @var \Application\Models\Comment
         */
        $commentM = Model::get('\Application\Models\Comment');
        $comments = $commentM->getComments(['feed' => $feedId], $limit, $lastId - 1);
        $comments = CommentHelper::prepare($comments);

        $data = [];
        $ids = [];

        if ( !empty($comments) )
        {
            foreach ( $comments['feed'][$feedId] as $comment )
            {
                $ids[] = $comment['id'];

                $view = new View();
                $view->set('Feed/comment', [
                    'userInfo' => $this->user->getInfo(),
                    'comment' => $comment
                ]);
                $data[] = $view->content();
            }
            
        }

        throw new ResponseJSON('success', [
            'dataAvailable' => $limit == count($data),
            'ids' => $ids,
            'comments' => $data
        ]);
    }
}