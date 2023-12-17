<?php

namespace System\Core;

use System\Core\Exceptions\SystemError;

abstract class Model {

    private static $_instances = [];

    /**
     * Get a model instance
     * 
     * @param string            $name       Pass the model
     * @param null|string       $host       Pass the host name from where model should get the options.
     *                                      Default will be decided by the framework
     * 
     * @var return mixed
     */
    public static function get( $name, $host = null )
    {
        $name = self::_prepareName($name);     

        if ( ! isset(self::$_instances[$name]) )
        {
            self::$_instances[$name] = $name::instance( $name, $host );
        }

        return self::$_instances[$name];
    }

    public static function isInitialized( $name )
    {
        return isset(self::$_instances[$name]);
    }

    /**
     * @var \System\Core\Database
     */
    protected $_db;

    protected function __construct( $options = null )
    {
        $this->_db = Database::get();
    }

    protected static function instance( $name, $host )
    { 
        // before creating the instance
        // get the options if set any.
        $modelList = Application::config()->enable_system_modules;
        $host = !$host ? Application::host() : $host;
        $options = array();

        // Remember host will not be set in cli mode
        if (
            $host &&
            isset($modelList[$host]) &&
            isset($modelList[$host][$name]) ) $options = $modelList[$host][$name];
        
        /**
         * Check for dependencies for this model
         */
        if ( property_exists($name, 'dependencies') )
        {
            $error = self::_checkDependencies( $name::$dependencies );
            if ( !empty($error) ) throw new SystemError(
                "$name requires the following packages to work: " . $error
            );
        }

        return new static( $options );
    }

    private static function _checkDependencies( $list )
    {
        $output = "";
        foreach ( $list as $key => $item )
        {
            if ( !@class_exists($key) )
            {
                $output .= "[{$item['name']} ({$item['install']}) {$item['link']}] ";
            }
        }

        return $output;
    }

    private static function _prepareName( $name )
    {
        if ( substr($name, 0, 1) !== '\\' ) $name = '\\' . $name;

        return $name;
    }
}