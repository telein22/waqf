<?php

namespace Application\Models;

use System\Core\Model;

class ServiceLog extends Model
{
    const ACTION_START = 'start';
    const ACTION_JOIN = 'join';

    const TYPE_SYSTEM = 'system';
    const TYPE_USER = 'user';

    private $_table = 'service_log';

    public function create( $data )
    {
        $data['action_by'] = isset($data['type']) && $data['type'] == self::TYPE_SYSTEM ? 0 : $data['action_by'];
        return $this->_db->insert($this->_table, $data);
    }

    public function getByEntity( $entityId, $entityType )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `entity_id` = ? AND `entity_type` = ?";
        return $this->_db->query($SQL, [$entityId, $entityType])->getAll();
    }
}