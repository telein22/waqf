<?php

namespace Application\Models;

use System\Core\Model;

class RememberToken extends Model
{  
    private $_table = 'remember_token';

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByToken( $token )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `token` = ?";
        return $this->_db->query($SQL, [$token])->get();
    }

    public function removeByToken( $token )
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `token` = ?";
        return $this->_db->query($SQL, [$token]);
    }
}