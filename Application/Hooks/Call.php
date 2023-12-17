<?php

namespace Application\Hooks;

use Application\Models\CallRequest;
use Application\Models\Email;
use Application\Models\Language;
use System\Core\Model;
use Application\Models\Notification;
use Application\Models\Participant;
use Application\Models\CallSlot as ModelsCallSlot;
use Application\Models\Call as ModelsCall;
use Application\Models\Meeting;
use Application\Models\User;
use System\Helpers\URL;
use Application\Models\Notification as NotificationModel;
use Application\Models\Queue as QueueModel;

class Call
{

    public function onJoin($data)
    {
        $call = $data['item'];

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get(User::class);
        $userInfo = $userM->getUser($call['user_id']);

        $fields = [
            'allowStartStopRecording' => false,
            'autoStartRecording' => false,
            'fullName' => $userInfo['name'],
            'record' => false,
            'welcome' => 'Hi ' . $userInfo['name'],
            'moderatorName' => $userInfo['name'],
            'duration' => 30,
            'scheduleTime' => strtotime( $call['date'] . ' ' . $call['time'] ),
            'serverURL' => 'https://scale.telein.net'
        ];

        $postdata = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://bigapi.telein.net/createMeeting.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        
        $row = json_decode($result, true);

        /**
         * @var \Application\Models\Meeting
         */
        $meetingM = Model::get(Meeting::class);
        $result = $meetingM->create(array(
            'call_slot_id' => $id,
            'meeting_id' => $row['MeetingId'],
            'meeting_url' => $row['JoinMeetingURL']
        ));
    }

    public function upcomingOrCurrent($collection)
    {
        $userId = User::getId();
        $callM = Model::get(ModelsCall::class);
        $call = $callM->upcomingOrCurrent($userId);
        $user = null;

        if (isset($call['owner_id'])) {
            $userM = Model::get(User::class);
            $user = $userM->getUser($call['owner_id']);
        }

        $collection->set('upcomingOrCurrentCall', [
            'call' => $call,
            'user' => $user,
            'isAdvisor' => isset($call['owner_id']) ? $call['owner_id'] == $userId : false,
        ]);
    }

    public function callRequest($data)
    {
        $callRequestModel = Model::get(CallRequest::class);
        $notificationModel = Model::get(NotificationModel::class);
        $queueM = Model::get(QueueModel::class);

        $callRequestId = $callRequestModel->create([
            'user_id' => $data['beneficiary']['id'],
            'advisor_id' => $data['serviceProviderUserId'],
            'preferences' => json_encode([
                'date1' => $data['date1'],
                'date2' => $data['date2'],
                'date3' => $data['date3'],
            ]),
            'status' => CallRequest::STATUS_ACTIVE,
            'created_at' => time()
        ]);

        $notificationData = [
            'sender_id' => $data['beneficiary']['id'],
            'receiver_id' => $data['serviceProviderUserId'],
            'type' => NotificationModel::TYPE_SERVICE,
            'action_type' => NotificationModel::ACTION_CALL_REQUEST,
            'data' => json_encode([
                'name' => $data['beneficiary']['name'],
                'date1' => $data['date1'],
                'date2' => $data['date2'],
                'date3' => $data['date3'],
                'callRequestId' => $callRequestId
            ]),
            'read' => 0,
            'sent' => 0,
            'created_at' => time()
        ];

        $notificationModel->create($notificationData);

        $queueData = [
            'user_id' => $data['serviceProviderUserId'],
            'entity_info' => [
                'name' => $data['beneficiary']['name'],
                'date1' => $data['date1'],
                'date2' => $data['date2'],
                'date3' => $data['date3'],
                'callRequestId' => $callRequestId
            ],
            'subject' => 'call_request',
            'view' => 'call_request'
        ];

        $queueM->create([
            'type' => QueueModel::TYPE_EMAIL,
            'data' => json_encode($queueData),
            'priority' => 5,
            'created_at' => time()
        ]);

        return $callRequestId;
    }
}
