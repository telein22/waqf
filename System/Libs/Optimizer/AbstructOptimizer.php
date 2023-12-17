<?php

namespace System\Libs\Optimizer;

use System\Libs\Optimizer\IDriver;

abstract class AbstructOptimizer
{

    protected $_driver;

    public function __construct( IDriver $driver )
    {
        $this->_driver = $driver;
    }

    public function optimize( Parameters $parameters )
    {
        $params = $parameters->parse();
        foreach ( $params as $key => $values )
        {
            call_user_func_array(array($this->_driver, 'command_' . strtolower($key)), $values);
        }
    }

    public function getDriver()
    {
        return $this->_driver;
    }

    abstract public function content();
    
    abstract public function __destruct();
}