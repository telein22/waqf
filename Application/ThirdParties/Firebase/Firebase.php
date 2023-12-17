<?php

namespace Application\ThirdParties\Firebase;

use Application\Dtos\FirebaseNotification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Firebase
{
    public static function notify(FirebaseNotification $firebaseNotification)
    {
        try {
            $notificationConfigPath = dirname(dirname(__DIR__)) . "/Assets/firebase/sdk-config.json";

            if (!$firebaseNotification->getFcm()) {
                return;
            }

            $factory = (new Factory)
                ->withServiceAccount($notificationConfigPath);

            $messaging = $factory->createMessaging();
            $notification = Notification::create($firebaseNotification->getTitle(), $firebaseNotification->getBody());

            $arr = [];
            if (!empty($firebaseNotification->getData())) {
                $arr = array_merge([
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ], $firebaseNotification->getData());
            }

            $message = CloudMessage::new()->withChangedTarget('token', $firebaseNotification->getFcm())
                ->withNotification($notification)->withData($arr)->withHighestPossiblePriority();

            $messaging->send($message);
        } catch (\Throwable $e) {

        }
    }
}