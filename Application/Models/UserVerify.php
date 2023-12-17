<?php

namespace Application\Models;

use System\Core\Config;
use System\Core\Model;
use System\Helpers\Strings;

class UserVerify extends Model
{
    private $_table = 'user_verify';

    public function __construct()
    {
        parent::__construct();
    }

    public function hasToken( $userId, $type)
    {
        $SQL = "SELECT `expire_at` FROM `{$this->_table}`
            WHERE `user_id` = :uId AND `type` = :t";

        $result = $this->_db->query($SQL, [
            ':uId' => $userId,
            ':t' => $type
        ])->get();
        
        $isValid = false;
        if ( $result ) $isValid = $result['expire_at'] >= time();
        return $isValid;
    }

    public function createToken( $userId, $type, $data )
    {
        $random = strtoupper(Strings::random(6));

        // get token expiry from config.
        $config = Config::get("Website");
        $expire = $config->expire_verification_token ? $config->expire_verification_token : 3600;
        $expire = time() + $expire;

        // delete previous token
        $SQL = sprintf("DELETE FROM %s WHERE `user_id` = :uId", $this->_table);
        $this->_db->query($SQL, [
            ':uId' => $userId
        ]);

        $result = $this->_db->insert($this->_table, array(
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
            'token' => $random,
            'expire_at' => $expire,
            'created_at' => time()
        ));

        return $result ? $random : false;
    }

    public function verifyToken( $userId, $type, $token )
    {
        if ( !$this->hasToken($userId, $type) ) return false;

        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `user_id` = :uId AND `type` = :t AND `token` = :token";

        $result = $this->_db->query($SQL, array(
            ':uId' => $userId,
            ':t' => $type,
            ':token' => $token
        ))->get();

        return $result;
    }
}