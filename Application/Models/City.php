<?php

namespace Application\Models;

use System\Core\Model;

class City extends Model
{
    private $_table = 'cities';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByCountry( $countryId )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `country_id` = ?
                ORDER BY `en_name` ASC";
        
        return $this->_db->query($SQL, [$countryId])->getAll();
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` = ?";
        
        return $this->_db->query($SQL, [$id])->get();
    }
}