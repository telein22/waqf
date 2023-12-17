<?php

namespace System\Core;

use System\Core\Exceptions\SystemError;

class Config {

    private static $_configs = [];

    public static function get( string $namespace ) : ConfigObject
    {
        if ( !isset(self::$_configs[$namespace]) )
        {
            self::$_configs[$namespace] = new ConfigObject($namespace);
        }

        return self::$_configs[$namespace];
    }

    private function __construct(){}
}

class ConfigObject {
    
    private $_namespace;

    private $_configs = [];

    public function __construct( $namespace )
    {
        $this->_namespace = $namespace;
    }

    public function set( array $array )
    {
        $this->_configs = $array;
    }

    public function getAll()
    {
        return $this->_configs;
    }

    public function __get($name)
    {
        return isset($this->_configs[$name]) ? $this->_configs[$name] : null;
    }
}