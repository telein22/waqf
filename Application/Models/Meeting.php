<?php

namespace Application\Models;

use System\Core\Model;

class Meeting extends Model
{  
    private $_table = 'meetings';

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByEntity( $entityId, $entityType ) 
    {
        $SQL = "SELECT * FROM `{$this->_table}` 
                WHERE `entity_id` = ?
                AND `entity_type` = ?";

        return $this->_db->query($SQL, [$entityId, $entityType])->get();
    }

    public function setMeetingAuthToken(int $id, string $meetingURL)
    {
        $SQL = "UPDATE `{$this->_table}` SET `meeting_url` = ?
                WHERE `id` = ?";

        $this->_db->query($SQL, [$meetingURL, $id]);
    }
}