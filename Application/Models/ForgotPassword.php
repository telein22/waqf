<?php

namespace Application\Models;

use System\Core\Model;

class ForgotPassword extends Model
{  
    private $_table = 'forgot_password';

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function updateByEmail( $email, $otp )
    {
        $SQL = "UPDATE `{$this->_table}` 
                SET `otp` = ?
                WHERE `email` = ?";

        return $this->_db->query($SQL, [$otp, $email]);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function exists( $email )
    {
        $SQL = "SELECT 1 FROM `{$this->_table}` WHERE `email` = ?";
        return (bool) $this->_db->query($SQL, [$email])->rowCount();
    }

    public function verify( $email, $otp )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `email` = ? AND `otp` = ?";
        return $this->_db->query($SQL, [$email, $otp])->get();
    }
}