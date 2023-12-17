<?php

namespace System\Libs\Hooks;

use InvalidArgumentException;
use System\Libs\Hooks;

class Dispatcher
{
    private $_event;

    private $_params;

    private $_hooks;

    private $_isResolved = false;

    public function __construct( $event, $params, Hooks $hooks )
    {
        $this->_event = $event;
        $this->_params = $params;
        $this->_hooks = $hooks;
    }

    public function later()
    {
        $this->_hooks->setDispatcher( $this );
    }

    public function now()
    {
        // resolve it now.
        return $this->resolve();
    }

    public function isResolved()
    {
        return $this->_isResolved;
    }

    public function resolve()
    {
        // Now we need to resolve the hooks.
        $binds = $this->_hooks->getBinds($this->_event);

        $hooks = [];
        $outputs = [];

        foreach ( $binds as $bind )
        {
            $matches = array();
            $result = preg_match('/^([A-z0-9]+)::([A-z0-9]+)$/', $bind, $matches);            
            if ( ! $result ) throw new InvalidArgumentException("Invalid hook pattern is supplied please check: " . $bind);

            if ( !isset($hooks[$matches[1]]) ) $hooks[$matches[1]] = new $matches[1];

            $outputs[$matches[1] . '::' . $matches[2]] = call_user_func([$hooks[$matches[1]], $matches[2]], $this->_params);
        }

        $this->_isResolved = true;

        return $outputs;
    }
}