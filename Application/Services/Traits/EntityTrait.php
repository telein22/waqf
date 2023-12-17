<?php

namespace Application\Services\Traits;

use Application\Dtos\BaseItem;
use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\AppHelper;
use Application\Models\Call;
use Application\Models\Language;
use Application\Models\Meeting;
use Application\Models\Participant;
use Application\Models\ServiceLog;
use Application\Models\User;
use Application\Models\Workshop;
use Application\ThirdParties\Firebase\Firebase;
use Application\ThirdParties\MeetingProviders\BigBlueButton\BigBlueButtonProvider;
use Application\ThirdParties\MeetingProviders\Dyte\DyteProvider;
use Application\ThirdParties\MeetingProviders\Zoom\ZoomProvider;
use Pusher\Pusher;
use System\Core\Application;
use System\Core\Model;

trait EntityTrait
{
    public function onStart(string $type, BaseItem $entityDto)
    {
        $meetingProvider = null;
        $meetingM = Model::get(Meeting::class);

        if (AppHelper::isMeetingProvider(AppHelper::DYTE_PROVIDER)) {
            $meetingProvider = new DyteProvider($entityDto);
            $meetingProvider->setUp();
        } elseif (AppHelper::isMeetingProvider(AppHelper::ZOOM_PROVIDER)) {
            $meetingProvider = new ZoomProvider($entityDto);
            $meetingProvider->setUp();
        } elseif (AppHelper::isMeetingProvider(AppHelper::BIG_BLUE_BUTTON_PROVIDER)) {
            if ($type == Call::ENTITY_TYPE) {
                $meetingProvider = new ZoomProvider($entityDto);
                $meetingProvider->setUp();
            } else {
                $meetingProvider = new BigBlueButtonProvider($entityDto);
                $meetingProvider->setUp();
            }
        }

        $meetingM->create([
            'entity_id' => $entityDto->getId(),
            'entity_type' => $type,
            'meeting_id' => $meetingProvider->getMeetingId(),
            'meeting_url' => $meetingProvider->getAdvisorMeetingUrl(),
//            'meeting_type' => $type == Call::ENTITY_TYPE ? AppHelper::ZOOM_PROVIDER : AppHelper::getDefaultMeetingProvider()
            'meeting_type' => AppHelper::getDefaultMeetingProvider()
        ]);

        // user service started.
        $serviceM = Model::get(ServiceLog::class);
        $serviceM->create([
            'type' => ServiceLog::TYPE_USER,
            'action' => ServiceLog::ACTION_START,
            'entity_id' => $entityDto->getId(),
            'entity_type' => $type,
            'action_by' => $entityDto->getUserId(),
            'created_at' => time()
        ]);

        $status = null;
        if ($type == 'workshop') {
            $workM = Model::get(Workshop::class);
            $workshop = $workM->getInfoById($entityDto->getId());
            $status = $workshop['status'];

        } elseif ($type == 'call') {
            $callM = Model::get(Call::class);
            $call = $callM->getById($entityDto->getId());
            $status = $call['status'];
        }

        if ($status == 'current') {
            $this->notifyOnStart($type, $entityDto);
        }
    }

    public function notifyOnStart(string $type, BaseItem $entityDto)
    {
        $lang = Model::get(Language::class);
        $pusherConf = Application::config()->Pusher;
        $pusher = new Pusher(
            $pusherConf['key'],
            $pusherConf['secret'],
            $pusherConf['app_id'],
            ['cluster' => $pusherConf['cluster']]
        );

        $pusher->trigger("notifications.{$type}.{$entityDto->getId()}", "{$type}_started", [
            'entity_id' => $entityDto->getId()
        ]);

        $participantM = Model::get(Participant::class);
        $participants = $participantM->all($entityDto->getId(), $type);
        foreach ($participants as $participant) {
            $userM = Model::get(User::class);
            $userInfo = $userM->getUser($participant['user_id']);

            Firebase::notify(new FirebaseNotification($userInfo['fcm_token'], $lang('incoming_notification'), " ",
                new FirebaseNotificationData("{$type}_started", $entityDto->getId())
            ));
        }
    }
}