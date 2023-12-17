<?php

class Autoloader {


    public function start()
    {
        spl_autoload_register(array($this, 'load'));
    }

    public function preload( array $preloads )
    {
        foreach ( $preloads as $v )
        {
            $this->load($v);
        }
    }

    public function load( $name )
    {
        $path = str_replace('\\', DS, $name);
        include ABS_PATH . DS . $path . '.php';
    }
}