<?php

namespace System\Core;

abstract class AbstractInput
{
    public function get( $var, $default = null, array $options = array() )
    {
        return isset($_GET[$var]) ? $_GET[$var] : $default;
    }

    public function post( $var, $default = null, array $options = array() )
    {
        return isset($_POST[$var]) ? $_POST[$var] : $default;
    }
}