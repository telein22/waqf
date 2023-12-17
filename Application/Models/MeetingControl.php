<?php

namespace Application\Models;

use System\Core\Model;
use Application\Dtos\Meeting as MeetingDto;
class MeetingControl extends Model
{
    private $_table = 'meeting_controls';

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function getUserMeetingControls(int $userId, MeetingDto $meetingDto)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `user_id` = ? AND `meeting_id` = ?";

        return $this->_db->query($SQL, [$userId, $meetingDto->getId()])->get();
    }

    public function updateSessioInfo(int $id, string $sessionKey, string $sessionExpiration)
    {
        $SQL = "UPDATE `{$this->_table}` SET `session_key` = ? , `session_expiry_at` = ? WHERE `id` = ?";

        return $this->_db->query($SQL, [$sessionKey, $sessionExpiration, $id]);
    }
}