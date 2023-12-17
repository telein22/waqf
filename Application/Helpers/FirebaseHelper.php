<?php

namespace Application\Helpers;

use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Models\Language;
use Application\Models\User;
use Application\ThirdParties\Firebase\Firebase;
use System\Core\Model;

class FirebaseHelper
{
    public static function notify(string $senderId, string $receiverId, string $actionType, FirebaseNotificationData $data): void
    {
        $lang = Model::get(Language::class);
        $userM = Model::get(User::class);
        $sender = $userM->getUser($senderId);
        $receiver = $userM->getUser($receiverId);

        $body = $lang('notification_' . $actionType, ['name' => $sender['name']]);
        $body = str_replace("<strong>", "", $body);
        $body = str_replace("</strong>", "", $body);

        Firebase::notify(new FirebaseNotification(
            $receiver['fcm_token'],
            $lang('incoming_notification'),
            $body,
            $data));
    }
}