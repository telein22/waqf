<?php

namespace Application\Models;

use System\Core\Model;

class HiddenEntities extends Model
{  
    private $_table = 'hidden_entities';

    public function getTable()
    {
        return $this->_table;
    }

    public function isHidden( $entityId, $entityType )
    {
        $SQL = "SELECT 1 FROM `{$this->_table}` WHERE `entity_id` = ? AND `entity_type` = ?";

        return (bool) $this->_db->query($SQL, [ $entityId, $entityType ])->rowCount();
    }

    public function listByType( $type )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `entity_type` = ?
        ORDER BY `id` DESC";

        return $this->_db->query($SQL, [$type])->getAll();
    }

    public function list( $id, $type )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `entity_id` = ?
        AND `entity_type` = ?
        ORDER BY `id` DESC";

        return $this->_db->query($SQL, [$id, $type])->getAll();
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function delete( $id )
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `entity_id` = ?";

        return $this->_db->query($SQL, [$id]) ;
    }

}