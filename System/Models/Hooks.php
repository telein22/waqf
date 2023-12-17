<?php

namespace System\Models;

use System\Libs\Hooks as LibsHooks;
use \System\Core\Model;

class Hooks extends Model
{
    private $_hooks;

    public function __construct( $options = null )
    {
        parent::__construct( $options );

        $this->_hooks = LibsHooks::instance();

        $binds = isset($options['binds']) ? $options['binds'] : [];
        $this->_bindAll($binds);
    }

    private function _bindAll( $binds )
    {
        if ( !$binds ) return;

        $this->_hooks->setBinds($binds);
    }

    public function dispatch( $event, $params = null )
    {
        return $this->_hooks->dispatch($event, $params);
    }

}