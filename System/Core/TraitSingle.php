<?php

namespace System\Core;

trait TraitSingle
{
    private static $_instance;

    public static function instance( $params = null )
    {
        if ( self::$_instance == null )
        {
            self::$_instance = new self($params);
        }

        return self::$_instance;
    }
}