<?php

namespace Application\Models;

use System\Core\Model;

class Reminder extends Model
{  
    private $_table = 'reminder';

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByEntity( $id, $type )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ? AND `entity_type` = ?";
        
        return $this->_db->query($SQL, [$id, $type])->getAll();
    }
}