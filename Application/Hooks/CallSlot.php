<?php

namespace Application\Hooks;

use Application\Models\User;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\Model;
use Application\Models\CallRequest;
use Application\Models\CallSlot as CallSlotModel;

class CallSlot
{

    public function onCreate($id)
    {
        $callSM = Model::get(CallSlotModel::class);
        $callSlot = $callSM->getById($id);

        $callRequestM = Model::get(CallRequest::class);
        $advisorId = $callSlot['user_id'];

        $activeCallRequests = $callRequestM->getActiveCallRequests($advisorId);
        $userM = Model::get(User::class);

        foreach ($activeCallRequests as $callRequest) {
            $user = $userM->getUser($callRequest['user_id']);
            $advisor = $userM->getUser($callRequest['advisor_id']);

            if ($user && $advisor) {
                $message = WhatsappMessages::confirmCallRequestHasBeenClosed($user['name'], $advisor['id'], $advisor['name']);
                Whatsapp::sendChat($user['phone'], $message);
            }
        }

        $callRequestM->markRequestsAsClosed($advisorId);
    }
}
