<?php

namespace Application\Hooks;

use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\FirebaseHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Notification as ModelsNotification;
use Application\Models\User;
use Application\Models\Workshop;
use Application\ThirdParties\Firebase\Firebase;
use Application\Models\Language;
use System\Core\Model;

class Notification
{
    public function unreadCount($collection)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getInfo();

        /**
         * @var \Application\Models\Notification
         */
        $notifiM = Model::get('\Application\Models\Notification');
        $notifiM->updateSent( $userInfo['id'] );

        $dataService = $notifiM->countUnseen($userInfo['id'], 'service');
        $dataSocial = $notifiM->countUnseen($userInfo['id'], 'social');

        $data = array(
            'service' => $dataService,
            'social' => $dataSocial
        );

        $collection->set('notification.unreadCount', $data);
    }

    public function onFollow($data)
    {
        $notifiM = Model::get('\Application\Models\Notification');
        $notifiM->create($data);

        $notificationData = json_decode($data['data'], true);
        FirebaseHelper::notify($data['sender_id'], $data['receiver_id'], $data['action_type'],
            new FirebaseNotificationData('profile', $notificationData['follow_id']));
    }

    public function onLike($data)
    {
        $notifiM = Model::get('\Application\Models\Notification');
        $notifiM->create($data);

        $notificationData = json_decode($data['data'], true);
        FirebaseHelper::notify($data['sender_id'], $data['receiver_id'], $data['action_type'],
            new FirebaseNotificationData('feed', $notificationData['feed_id']));
    }

    public function onComment($data)
    {
        $notifiM = Model::get('\Application\Models\Notification');
        $notifiM->create($data);

        $notificationData = json_decode($data['data'], true);
        FirebaseHelper::notify($data['sender_id'], $data['receiver_id'], $data['action_type'],
            new FirebaseNotificationData('feed', $notificationData['feed_id']));
    }

    public function orderCancel( $order )
    {
        $isAdvisor = $order['user_id'] !== User::getId();

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Workshop
                 */
                $workshopM = Model::get('\Application\Models\Workshop');
                $workshopInfo = $workshopM->getInfoById( $order['entity_id'] );

                $actionType = $isAdvisor ? ModelsNotification::ACTION_WORKSHOP_REJECTED :
                    ModelsNotification::ACTION_WORKSHOP_USER_CANCELED;

                $data = array(
                    'sender_id' => $isAdvisor ? $workshopInfo['user_id'] : $order['user_id'],
                    'receiver_id' => $isAdvisor ? $order['user_id'] : $workshopInfo['user_id'],
                    'type' => ModelsNotification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($workshopInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time()
                );

                $notifiM = Model::get('\Application\Models\Notification');
                $data = $notifiM->create($data);
                break;
            case Call::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $callInfo = $callM->getById( $order['entity_id'] );

                $actionType = $isAdvisor ? ModelsNotification::ACTION_CALL_REJECTED :
                    ModelsNotification::ACTION_CALL_USER_CANCELED;

                $data = array(
                    'sender_id' => $isAdvisor ? $callInfo['owner_id'] : $order['user_id'],
                    'receiver_id' => $isAdvisor ? $order['user_id'] : $callInfo['owner_id'],
                    'type' => ModelsNotification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($callInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time()
                );

                $notifiM = Model::get('\Application\Models\Notification');
                $data = $notifiM->create($data);


                break;
            case Conversation::ENTITY_TYPE:

                 /**
                 * @var \Application\Models\Conversation
                 */
                $conversationM = Model::get('\Application\Models\Conversation');
                $convoInfo = $conversationM->getById( $order['entity_id'] );

                $actionType = $isAdvisor ? ModelsNotification::ACTION_MESSAGE_REJECTED :
                    ModelsNotification::ACTION_MESSAGE_USER_CANCELED;

                $data = array(
                    'sender_id' => $isAdvisor ? $convoInfo['owner_id'] : $order['user_id'],
                    'receiver_id' => $isAdvisor ? $order['user_id'] : $conversationM['owner_id'],
                    'type' => ModelsNotification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($convoInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time()
                );

                $notifiM = Model::get('\Application\Models\Notification');
                $data = $notifiM->create($data);

                break;
        }
    }
}
