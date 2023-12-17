<?php

namespace Application\Models;

use System\Core\Model;

class Charity extends Model
{  
    const ENTITY_TYPE = 'charities';
    private $_table = 'charities';

    public function all()
    {
        $SQL = "SELECT * FROM `{$this->_table}`";

        return $this->_db->query($SQL)->getAll();
    }

    public function getByIds( $ids )
    {
        if ( empty($ids) ) return [];
        
        $ids = (array) $ids;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `id` IN ";
        
        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();
        
        if ( !$result ) return [];

        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function getCharityById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = '$id'";
        return $this->_db->query($SQL)->get();
    }

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }
}