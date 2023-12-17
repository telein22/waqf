<?php

namespace Application\Models;

use System\Core\Model;

class Country extends Model
{
    private $_table = 'countries';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getAll()
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                ORDER BY `id` ASC";
        
        return $this->_db->query($SQL)->getAll();
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` = ?";
        
        return $this->_db->query($SQL, [$id])->get();
    }
}