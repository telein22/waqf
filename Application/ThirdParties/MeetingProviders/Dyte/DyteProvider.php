<?php

namespace Application\ThirdParties\MeetingProviders\Dyte;

use Application\Dtos\BaseItem;
use Application\Helpers\UserHelper;
use Application\Models\Meeting;
use Application\Models\MeetingControl;
use Application\Models\Participant;
use Application\Dtos\Meeting as MeetingDto;
use Application\ThirdParties\MeetingProviders\MeetingProvider;
use DateInterval;
use DateTime;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\Strings;
use System\Models\Session;

class DyteProvider implements MeetingProvider
{
    private const BASE_API_URL = 'https://api.dyte.io/v2/';
    private const CREATE_MEETING_URI = 'meetings';
    private const ADD_PARTICIPANT_URI = "meetings/?/participants";

    private string $dyteBaseUrl;
    private $item;
    private $config;
    private $authorization;
    public $meetingInfo;
    public $joiningInfo;

    private $userInfo;

    public function __construct(BaseItem $item)
    {
        $this->item = $item;
        $this->config = Config::get("Application")->Dyte;
        $this->authorization = $this->config['organization_id'] . ':' . $this->config['api_key'];
        $this->authorization = base64_encode($this->authorization);
        $this->dyteBaseUrl = Config::get('Website')->dyte_provider_base_url;

        $session = Model::get(Session::class);
        $this->userInfo = $session->take('userInfo');
    }

    public function setUp()
    {
        $url = self::BASE_API_URL . self::CREATE_MEETING_URI;
        $this->meetingInfo = $this->callAPI($url, [
            'title' => $this->item->getName(),
            'preferred_region' => 'ap-south-1',
            'record_on_start' => false,
            'live_stream_on_start' => false
        ]);
    }

    public function setUpJoin(MeetingDto $meeting)
    {
        $meetingControlM = Model::get(MeetingControl::class);
        $meetingControl = $meetingControlM->getUserMeetingControls($this->userInfo['id'], $meeting);
        $sessionKey = $this->getSessionKey($meeting);
        $expiryAt = $this->getSessionExpiryAt();

        if ($meetingControl) {
            $meetingControlM->updateSessioInfo($meetingControl['id'], $sessionKey, $expiryAt);

            return json_encode([
                'JoinMeetingURL' => "{$this->dyteBaseUrl}?session_key={$sessionKey}",
                'Advisor_url' => "{$this->dyteBaseUrl}?session_key={$sessionKey}"
            ]);
        }

        $presetName = 'group_call_host';

        if ($this->userInfo['id'] != $this->item->getUserId()) {
            $presetName = 'group_call_participant';
        }

        $url = self::BASE_API_URL . self::ADD_PARTICIPANT_URI;
        $url = str_replace('?', $meeting->getMeetingId(), $url);
        $this->joiningInfo = $this->callAPI($url, [
            'name' => $this->userInfo['name'],
            'picture' => UserHelper::getAvatarUrl('fit:300,300', $this->userInfo['id']),
            'preset_name' => $presetName,
            'custom_participant_id' => $this->userInfo['id']
        ]);

        $meetingControlM->create([
            'meeting_id' => $meeting->getId(),
            'user_id' => $this->userInfo['id'],
            'auth_token' => $this->joiningInfo['data']['token'],
            'session_key' => $sessionKey,
            'session_expiry_at' => $expiryAt
        ]);

        return json_encode([
            'JoinMeetingURL' => "{$this->dyteBaseUrl}?session_key={$sessionKey}",
            'Advisor_url' => "{$this->dyteBaseUrl}?session_key={$sessionKey}"
        ]);
    }

    public function getMeetingId(): string
    {
        return $this->meetingInfo['data']['id'];
    }

    public function getAdvisorMeetingUrl(): string
    {
        return  '';
    }

    public function getAttendeMeetingUrl(): string
    {
        return '';
    }

    private function callAPI(string $url, array $data): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Basic {$this->authorization}"
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    private function getSessionKey(MeetingDto $meeting): string
    {
        $meetingId = $meeting->getId();
        $userId = $this->userInfo['id'];
        return Strings::random(16) . "{$userId}{$meetingId}";
    }

    private function getSessionExpiryAt(): string
    {
        $meetingLinkExpiry = Config::get('Website')->meeting_link_expiry;
        $currentDateTime = new DateTime();
        $currentDateTime->add(new DateInterval("PT{$meetingLinkExpiry}M"));

        return $currentDateTime->format('Y-m-d H:i:s');
    }
}