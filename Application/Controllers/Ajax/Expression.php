<?php

namespace Application\Controllers\Ajax;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use Application\Models\Notification as ModelsNotification;

class Expression extends AuthController
{
    public function toggle( Request $request, Response $response )
    {
        $entityId = $request->post('entityId');
        $entityType = $request->post('entityType');
        $type = $request->post('type');

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Expression
         */
        $expressM = Model::get('\Application\Models\Expression');
        $is = $expressM->isExpressed($userInfo['id'], [ $entityType => [$entityId] ], $type);
        
        $feedM = Model::get('\Application\Models\Feed');
        $feedInfo = $feedM->getFeed( $entityId );


        if ( empty($is) )
        {
            // express new
            $expressM->create(array(
                'user_id' => $userInfo['id'],
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'type' => $type,
                'created_at' => time()
            ));

            $json = json_encode(array( 'feed_id' => $entityId ));

            $data = array(
                'sender_id' => $userInfo['id'],
                'receiver_id' => $feedInfo['user_id'],
                'type' => 'social',
                'action_type' => ModelsNotification::ACTION_FEED_LIKE,
                'read' => 0,
                'sent' => 0,
                'data' => $json,
                'created_at' => time()
            );

            if( $feedInfo['user_id'] != $userInfo['id'] )
            {
                $this->hooks->dispatch('feed.on_like', $data)->later();
            }

        } else {            
            // delete expression
            $expressM->delete($entityType, $entityId, $type, $is['id']);
        }

        throw new ResponseJSON('success');
    }
}