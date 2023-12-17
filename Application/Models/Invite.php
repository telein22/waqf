<?php

namespace Application\Models;

use System\Core\Model;

class Invite extends Model
{
    const JOIN_TYPE_NORMAL = 'normal';
    const JOIN_TYPE_FREE = 'free';

    private $_table = 'invites';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function isInvited( $userId, $entityId, $entityType )
    {
        $SQL = "SELECT `type` FROM `{$this->_table}`
            WHERE `user_id` = ? AND `entity_id` = ?
            AND `entity_type` = ?";

        $result = $this->_db->query($SQL, [$userId, $entityId, $entityType])->get();

        return $result ? $result['type'] : null;
    }

}