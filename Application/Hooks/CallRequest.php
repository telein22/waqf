<?php

namespace Application\Hooks;

use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\Model;
use Application\Models\CallRequest as CallRequestModel;
use Application\Models\Notification;
use Application\Models\Notification as NotificationModel;
use Application\Models\Queue as QueueModel;

class CallRequest
{
    public function onHandle($data)
    {
        $advisor = $data['advisor'];
        $beneficiary = $data['beneficiary'];
        $callRequest = $data['callRequest'];

        $notificationModel = Model::get(NotificationModel::class);
        $queueM = Model::get(QueueModel::class);

        $notificationData = [
            'sender_id' => $advisor['id'],
            'receiver_id' => $beneficiary['id'],
            'type' => NotificationModel::TYPE_SERVICE,
            'action_type' => NotificationModel::ACTION_CALL_REQUEST_RESOLVED,
            'data' => json_encode([
                'name' => $advisor['name'],
                'advisor_id' => $advisor['id']
            ]),
            'read' => 0,
            'sent' => 0,
            'created_at' => time()
        ];

        $notificationModel->create($notificationData);

        $queueData = [
            'user_id' => $beneficiary['id'],
            'entity_info' => [
                'name' => $advisor['name'],
                'advisor_id' => $advisor['id']
            ],
            'subject' => 'call_request_resolved',
            'view' => 'call_request_resolved'
        ];

        $queueM->create([
            'type' => QueueModel::TYPE_EMAIL,
            'data' => json_encode($queueData),
            'priority' => 5,
            'created_at' => time()
        ]);

        $message = WhatsappMessages::confirmCallRequestHasBeenClosed($beneficiary['name'], $advisor['id'], $advisor['name']);
        Whatsapp::sendChat($beneficiary['phone'], $message);

        $callRequestM = Model::get(CallRequestModel::class);
        $callRequestM->markRequestsAsClosedById($callRequest['id']);
    }
}
