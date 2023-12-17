<?php

namespace Application\ThirdParties\MeetingProviders\BigBlueButton;

use Application\Dtos\BaseItem;
use Application\Dtos\Meeting as MeetingDto;
use Application\Models\MeetingApi;
use Application\Models\User;
use Application\ThirdParties\MeetingProviders\MeetingProvider;
use System\Core\Config;
use System\Core\Model;

class BigBlueButtonProvider implements MeetingProvider
{
    public $meetingInfo;
    protected $item;
    private $config;
    private $userInfo;

    public function __construct(BaseItem $item)
    {
        $this->item = $item;
        $this->config = Config::get("Big");
        $this->userInfo = Model::get(User::class)->getInfo();
    }

    public function setUp()
    {
        $duration = time() - strtotime($this->item->getDate());
        $duration = $duration > 0 ? floor((($this->item->getDuration() * 60) - $duration) / 60) : $this->item->getDuration();

        // create a meeting
        $meetingData = [
            'url' => $this->config->create_meeting_url,
            'fields' => [
                'allowStartStopRecording' => 'true',
                'autoStartRecording' => 'false',
                'name' => $this->item->getName(),
                'record' => 'true',
                'welcome' => 'Hi ' . $this->userInfo['name'],
                'moderatorName' => $this->userInfo['name'],
                'duration' => $duration,
                'scheduleTime' => strtotime($this->item->getDate()),
                'serverURL' => $this->config->server_url,
                'userID' => $this->userInfo['id']
            ]
        ];


        $meetinApiM = Model::get(MeetingApi::class);
        $result = $meetinApiM->index($meetingData);
        $this->meetingInfo = json_decode($result, true);
    }

    public function setUpJoin(MeetingDto $meeting)
    {
        if (!$meeting) {
            return;
        }

        $meetingData = [
            'url' => $this->config->join_meeting_url,
            'fields' => [
                'serverURL' => $this->config->server_url,
                'name' => $this->userInfo['name'],
                'meetingID' => $meeting->getMeetingId(),
                'userID' => $this->userInfo['id']
            ]
        ];

        $meetinApiM = Model::get(MeetingApi::class);
        $result = $meetinApiM->index($meetingData);

        $this->meetingInfo = json_decode($result, true);
        $this->meetingInfo['Advisor_url'] = $meeting->getMeetingUrl();
        $this->meetingInfo = json_encode($this->meetingInfo);
    }

    public function getMeetingId(): string
    {
        return $this->meetingInfo['MeetingId'];
    }

    public function getAdvisorMeetingUrl(): string
    {
        return $this->meetingInfo['JoinMeetingURL'];
    }

    public function getAttendeMeetingUrl(): string
    {
        return $this->meetingInfo;
    }

}