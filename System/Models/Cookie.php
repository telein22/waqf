<?php

namespace System\Models;

use System\Core\Model;

class Cookie extends Model
{
    public function setCookie( $name, $value,  $expire = 0, $path = "", $domain = "", $secure = false, $httpOnly = true)
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function getCookie( $name )
    {
        return !empty($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public function removeCookie( $name )
    {
        if ( isset($_COOKIE[$name]) )
        {
            setcookie($name, "", time() - 3600);
        }
    }

}