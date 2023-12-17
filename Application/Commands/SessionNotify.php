<?php

namespace Application\Commands;

use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\FirebaseHelper;
use Application\Models\Participant;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Models\Language;
use Application\Models\Queue as QueueModel;
use Application\Models\User;
use Application\ThirdParties\Firebase\Firebase;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\CLICommand;
use System\Core\Config;
use System\Core\Model;
use Application\Models\Order;
use Application\Models\Call;
use Application\Models\Notification;
use Application\Models\Reminder;
use Application\Models\Workshop;
use Application\Helpers\AppHelper;


class SessionNotify extends CLICommand
{
    public function run($params)
    {
        $this->_notifyUsers();
    }

    private function _notifyUsers()
    {
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrders(null, null, null, Order::STATUS_APPROVED);

        foreach ($orders as $order) {
            $reminderM = Model::get(Reminder::class);
            $reminded = $reminderM->getByEntity($order['entity_id'], $order['entity_type']);
            if (!empty($reminded)) continue;

            switch ($order['entity_type']) {
                case Workshop::ENTITY_TYPE:
                    $this->_workshop($order['entity_id']);
                    break;
                case Call::ENTITY_TYPE:
                    $this->_call($order['entity_id']);
                    break;
            }
        }
    }

    // Final methods

    private function _call($id)
    {
        $callM = Model::get(Call::class);
        $calllInfo = $callM->getById($id);

        //TODO  Check for workshop status
        $emailReminderLimit = Config::get('Website')->call_email_reminder_limit;
        if ((strtotime($calllInfo['date']) - time()) <= $emailReminderLimit) {
            $reminderM = Model::get(Reminder::class);
            $reminderM->create([
                'entity_id' => $calllInfo['id'],
                'entity_type' => Call::ENTITY_TYPE
            ]);

            if ($calllInfo['status'] != Call::STATUS_NOT_STARTED) return;

            // Send to owner
            $arr = [
                'sender_id' => 0,
                'receiver_id' => $calllInfo['owner_id'],
                'type' => Notification::TYPE_SERVICE,
                'action_type' => Notification::ACTION_CALL_REMINDER,
                'data' => json_encode($calllInfo),
                'read' => 0,
                'sent' => 0
            ];

            $notiM = Model::get(Notification::class);
            $data = array_merge($arr, ['created_at' => time()]);
            $notiM->create($data);

            $queueM = Model::get(QueueModel::class);

            $userM = Model::get(User::class);
            $owner = $userM->getUser($calllInfo['owner_id']);
            $calllInfo['owner_name'] = $owner['name'];

            $arr = [
                'user_id' => $calllInfo['owner_id'],
                'entity_info' => $calllInfo,
                'subject' => 'call_reminder_subject',
                'view' => 'call_reminder'
            ];

            $queueM->create([
                'type' => QueueModel::TYPE_EMAIL,
                'data' => json_encode($arr),
                'priority' => 5,
                'created_at' => time()
            ]);

            // Send to beneficiary
            $arr = [
                'sender_id' => 0,
                'receiver_id' => $calllInfo['created_by'],
                'type' => Notification::TYPE_SERVICE,
                'action_type' => Notification::ACTION_CALL_REMINDER,
                'data' => json_encode($calllInfo),
                'read' => 0,
                'sent' => 0
            ];

            $notiM = Model::get(Notification::class);
            $data = array_merge($arr, ['created_at' => time()]);
            $notiM->create($data);

            $arr = [
                'user_id' => $calllInfo['created_by'],
                'entity_info' => $calllInfo,
                'subject' => 'call_reminder_subject',
                'view' => 'call_reminder'
            ];

            $queueM->create(array(
                'type' => QueueModel::TYPE_EMAIL,
                'data' => json_encode($arr),
                'priority' => 5,
                'created_at' => time()
            ));

            $userInfo = $userM->getUser($calllInfo['created_by']);

            $message = WhatsappMessages::reminderForUpcomingCall($userInfo['name'], $owner['name'], $calllInfo['id']);
            Whatsapp::sendChat($userInfo['phone'], $message);

            $message = WhatsappMessages::reminderForUpcomingCall($owner['name'], $userInfo['name'], $calllInfo['id']);
            Whatsapp::sendChat($owner['phone'], $message);

            /* Sending a reminder on Firebase */
            $body = "لديك مكالمة تيلي إن مع '{$owner['name']}' ستبدأ خلال 5 دقايق";
            Firebase::notify(new FirebaseNotification($userInfo['fcm_token'], 'تنبيه وارد', $body,
                new FirebaseNotificationData(FirebaseNotificationData::PAGE_CALL_WAITING_ROOM, $calllInfo['id'])));

            $body = "لديك مكالمة تيلي إن مع '{$userInfo['name']}' ستبدأ خلال 5 دقايق";
            Firebase::notify(new FirebaseNotification($owner['fcm_token'], 'تنبيه وارد', $body,
                new FirebaseNotificationData(FirebaseNotificationData::PAGE_CALL_WAITING_ROOM, $calllInfo['id'])));
        }
    }

    private function _workshop($id)
    {
        $workshopM = Model::get(Workshop::class);
        $workshopInfo = $workshopM->getInfoById($id);

        //TODO  Check for workshop status
        $emailReminderLimit = Config::get('Website')->session_email_reminder_limit;
        if ((strtotime($workshopInfo['date']) - time()) <= $emailReminderLimit) {
            $reminderM = Model::get(Reminder::class);
            $reminderM->create([
                'entity_id' => $workshopInfo['id'],
                'entity_type' => Workshop::ENTITY_TYPE
            ]);

            if ($workshopInfo['status'] != Workshop::STATUS_NOT_STARTED) return;

            $arr = [
                'sender_id' => 0,
                'receiver_id' => $workshopInfo['user_id'],
                'type' => Notification::TYPE_SERVICE,
                'action_type' => Notification::ACTION_WORKSHOP_REMINDER,
                'data' => json_encode($workshopInfo),
                'read' => 0,
                'sent' => 0
            ];

            $notiM = Model::get(Notification::class);
            $data = array_merge($arr, ['created_at' => time()]);
            $notiM->create($data);

            $queueM = Model::get(QueueModel::class);
            $arr = [
                'user_id' => $workshopInfo['user_id'],
                'entity_info' => $workshopInfo,
                'subject' => 'workshop_reminder',
                'view' => 'workshop_reminder'
            ];

            $queueM->create(array(
                'type' => QueueModel::TYPE_EMAIL,
                'data' => json_encode($arr),
                'priority' => 5,
                'created_at' => time()
            ));

            $participantM = Model::get(Participant::class);
            $participants = $participantM->all($id, Workshop::ENTITY_TYPE);
            $userM = Model::get(User::class);
            $workshopOwner = $userM->getUser($workshopInfo['user_id']);

            foreach ($participants as $participant) {
                $arr = [
                    'sender_id' => 0,
                    'receiver_id' => $participant['user_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => Notification::ACTION_WORKSHOP_REMINDER,
                    'data' => json_encode($workshopInfo),
                    'read' => 0,
                    'sent' => 0
                ];

                $data = array_merge($arr, ['created_at' => time()]);
                $notiM->create($data);

                $arr = [
                    'user_id' => $participant['user_id'],
                    'entity_info' => $workshopInfo,
                    'subject' => 'workshop_reminder_subject',
                    'view' => 'workshop_reminder'
                ];

                $queueM->create(array(
                    'type' => QueueModel::TYPE_EMAIL,
                    'data' => json_encode($arr),
                    'priority' => 5,
                    'created_at' => time()
                ));

                $userInfo = $userM->getUser($participant['user_id']);
                $this->notify($userInfo, $workshopInfo);
            }

            $this->notify($workshopOwner, $workshopInfo);
        }
    }

    private function notify(array $userInfo, array $workshopInfo): void
    {
        $message = WhatsappMessages::reminderForUpcomingWorkshop($userInfo['name'], $workshopInfo['id'], $workshopInfo['name']);
        Whatsapp::sendChat($userInfo['phone'], $message);

        $body = "لديك جلسة بعنوان '{$workshopInfo['name']}' ستبدأ بعد 15 دقيقة";
        Firebase::notify(new FirebaseNotification($userInfo['fcm_token'], 'تنبيه وارد', $body,
            new FirebaseNotificationData(FirebaseNotificationData::PAGE_WORKSHOP_WAITING_ROOM, $workshopInfo['id'])));
    }

}