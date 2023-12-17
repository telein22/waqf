<?php

namespace System\Libs;

use System\Libs\Hooks\Dispatcher;

class Hooks
{
    private $_binds = [];

    private $_dispatchers = [];

    private static $instance;

    /**
     * @return \System\Libs\Hooks
     */
    public static function instance()
    {
        if ( self::$instance == null )
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct( ){ }

    public function setBinds( $binds )
    {
        $this->_binds = $binds;
    }

    public function getBinds( $key )
    {
        return isset($this->_binds[$key]) ? (array) $this->_binds[$key] : [];
    }

    public function dispatch( $event, $params = null )
    {
        return new Dispatcher($event, $params, $this);
    }

    public function setDispatcher( $dispatcher )
    {
        $this->_dispatchers[] = $dispatcher;
    }

    public function dispatchPending()
    {
        foreach ( $this->_dispatchers as $dispatcher )
        {
            $dispatcher->resolve();
        }

        // Create dispatchers
        $this->_dispatchers = [];
    }

}