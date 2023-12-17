<?php

namespace Application\ThirdParties\MeetingProviders\Zoom;

use Application\Dtos\BaseItem;
use Application\Dtos\Meeting as MeetingDto;
use Application\Models\User;
use Application\ThirdParties\MeetingProviders\MeetingProvider;
use System\Core\Config;
use System\Core\Model;

class ZoomProvider implements MeetingProvider
{
    private $item;
    private $userInfo;
    private $zoomBaseUrl;

    public function __construct(BaseItem $item)
    {
        $this->item = $item;
    }

    public function setUp()
    {
        $userM = Model::get(User::class);

        if ($userM->isLoggedIn()) {
            $this->userInfo = $userM->getInfo();
        }

        $this->zoomBaseUrl = Config::get('Website')->zoom_provider_base_url;
    }

    public function setUpJoin(MeetingDto $meeting)
    {
        $this->setUp();

        return json_encode([
          'JoinMeetingURL' => $this->getAttendeMeetingUrl(),
          'Advisor_url' => $this->getAdvisorMeetingUrl()
        ]);
    }

    public function getMeetingId(): string
    {
        return "{$this->item->getType()}_{$this->item->getId()}";
    }

    public function getAdvisorMeetingUrl(): string
    {
        $topicName = $this->getMeetingId();
        $userM = Model::get(User::class);
        $entityUserName = $userM->getUser($this->item->getUserId());
        $time = strtotime($this->item->getDate() . ' + ' . $this->item->getDuration() . ' minute');
//        $time = strtotime(date('Y-m-d H:i:s') . " +  {$this->item->getDuration()}  minute");

        return "{$this->zoomBaseUrl}/?topic={$topicName}&name={$entityUserName['name']}&password=&time={$time}&sessionKey=&userIdentity=&role=1&cloud_recording_option=0&cloud_recording_election=&telemetry_tracking_id=&web=1";
    }

    public function getAttendeMeetingUrl(): string
    {
        if (!isset($this->userInfo['name'])) {
            return ' ';
        }

        $topicName = $this->getMeetingId();
        $time = strtotime($this->item->getDate() . ' + ' . $this->item->getDuration() . ' minute');

        return "{$this->zoomBaseUrl}/?topic={$topicName}&name={$this->userInfo['name']}&password=&time={$time}&sessionKey=&userIdentity=&role=0&cloud_recording_option=0&cloud_recording_election=&telemetry_tracking_id=&web=1";
    }

}