<?php

namespace Application\Helpers;

use Application\Models\Expression;
use Application\Models\Notification as ModelsNotification;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

class NotificationHelper
{
    public static function prepare($notifications)
    {
        $userM = Model::get('\Application\Models\User');

        $userIds = array();        
        $commentIds = array();
        $feedIds = array();
        $messageIds = array();
        // $workshopIds = array();

        foreach ($notifications as $notification) {
            $userIds[$notification['sender_id']] = $notification['sender_id'];
            $userIds[$notification['receiver_id']] = $notification['receiver_id'];

            $arr = json_decode($notification['data'], true);

            switch( $notification['action_type'] )
            {
                case ModelsNotification::ACTION_FEED_LIKE:
                    $feedIds[$arr['feed_id']] = $arr['feed_id'];
                    break;
                case ModelsNotification::ACTION_FEED_COMMENT:
                    $commentIds[$arr['comment_id']] = $arr['comment_id'];           
                    break;     
                case ModelsNotification::ACTION_MESSAGE_COMPLETED:
                    $messageIds[$arr['message_id']] = $arr['message_id'];
                    break;
                case ModelsNotification::ACTION_WORKSHOP_COMPLETED:
                    // $workshopIds[$arr['id']] = $arr['id'];
                    break;
                case ModelsNotification::ACTION_WORKSHOP_CANCELED:
                    // $workshopIds[$arr['id']] = $arr['id'];
                    break;
            }
        }

        $users = $userM->getInfoByIds($userIds);

        $commentM = Model::get('\Application\Models\Comment');
        $comments = $commentM->getCommentByIds($commentIds);

        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getFeedByIds($feedIds);

        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $messages = $msgM->getInfoByIds($messageIds);

        foreach ($notifications as &$notification) {
            $actionParams = [];

            $notification['sender'] = isset($users[$notification['sender_id']]) ? $users[$notification['sender_id']] : null;
            $notification['receiver'] = isset($users[$notification['receiver_id']]) ? $users[$notification['receiver_id']] : null;

            $arr = json_decode($notification['data'], true);

            switch ($notification['action_type']) {
                case ModelsNotification::ACTION_FOLLOW:
                    $actionParams[] = '\'' . URL::full('profile/' . $arr['follow_id']) . '\'';
                    break;

                case ModelsNotification::ACTION_FEED_LIKE:
                    $actionParams[] = '\'' . URL::full('feed/' . $arr['feed_id']) . '\'';
                    $feed = isset($feeds[$arr['feed_id']]) ? $feeds[$arr['feed_id']] : null;

                    if (!empty($feed)) {

                        $notification['preparedData'] = array(
                            'text' => $feed['text']
                        );
                    }

                    break;
                case ModelsNotification::ACTION_FEED_COMMENT:
                    $actionParams[] = '\'' . URL::full('feed/' . $arr['feed_id']) . '\'';
                    $comment = isset($comments[$arr['comment_id']]) ? $comments[$arr['comment_id']] : null;

                    if (!empty($comment)) {
                        $commentInfo = json_decode($comment['comment'], true);

                        $notification['preparedData'] = array(
                            'text' => $commentInfo['text']
                        );
                    }
                    break;
                case ModelsNotification::ACTION_MESSAGE_COMPLETED:
                    $actionParams[] = '\'' . URL::full('messaging/view/' . $arr['conversation_id']) . '\'';
                    $notification['preparedData'] = array(
                        'message' => isset($messages[$arr['message_id']]) ? $messages[$arr['message_id']] : null
                    );
                    break;
                case ModelsNotification::CRON_MESSAGE_REMINDER:
                    $actionParams[] = '\'' . URL::full('messaging/view/' . $arr['id']) . '\'';
                    break;
                case ModelsNotification::CRON_MESSAGE_CANCEL:
                    $actionParams[] = '\'' . URL::full('messaging/view/' . $arr['id']) . '\'';
                    break;
                case ModelsNotification::ACTION_MESSAGE_ACCEPTED:
                    $actionParams[] = '\'' . URL::full('messaging/view/' . $arr['id']) . '\'';
                    break;
                case ModelsNotification::ACTION_MESSAGE_REJECTED:
                    $actionParams[] = '\'' . URL::full('order/my') . '\'';
                    break;
                case ModelsNotification::ACTION_MESSAGE_PENDING:                    
                    $actionParams[] = '\'' . URL::full('messaging/a') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_COMPLETED:
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_CANCELED:                    
                    $notification['preparedData'] = [
                        'coupon' => $arr['cancel_coupon']
                    ];
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_COMPLETED:                                        
                    $actionParams[] = '\'' . URL::full('calls/b') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_REMINDER:                                        
                    $actionParams[] = '\'' . URL::full('calls/b') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_CANCELED:                                        
                    $actionParams[] = '\'' . URL::full('calls/b') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_ACCEPTED:                                        
                    $actionParams[] = '\'' . URL::full('calls/b') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_REJECTED:                                        
                    $actionParams[] = '\'' . URL::full('calls/b') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_PENDING:                    
                    $actionParams[] = '\'' . URL::full('workshops/a') . '\'';
                    $notification['preparedData'] = [
                        'workshop' => json_decode($notification['data'], true)
                    ];
                    break;
                case ModelsNotification::ACTION_CALL_PENDING:                    
                    $actionParams[] = '\'' . URL::full('calls/a') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_REQUEST:
                    $actionParams[] = '\'' . URL::full("calls/request/{$arr['callRequestId']}") . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_REQUEST_RESOLVED:
                    $actionParams[] = '\'' . URL::full("calls/find/{$arr['advisor_id']}") . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_REMINDER:                    
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    $notification['preparedData'] = [
                        'workshop' => json_decode($notification['data'], true)
                    ];
                    break;
                case ModelsNotification::ACTION_WORKSHOP_INVITED:                    
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::CRON_WORKSHOP_AUTO_CANCELED:                    
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_ACCEPTED:                    
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_REJECTED:                    
                    $actionParams[] = '\'' . URL::full('workshops/b') . '\'';
                    break;
                case ModelsNotification::ACTION_WORKSHOP_USER_CANCELED:
                    $actionParams[] = '\'' . URL::full('earnings') . '\'';
                    break;
                case ModelsNotification::ACTION_CALL_USER_CANCELED:
                    $actionParams[] = '\'' . URL::full('earnings') . '\'';
                    break;
                case ModelsNotification::ACTION_MESSAGE_USER_CANCELED:
                    $actionParams[] = '\'' . URL::full('earnings') . '\'';
                    break;
            }
            $notification['actionParams'] = $actionParams;
        }
        return $notifications;
    }
}
