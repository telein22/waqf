<?php

namespace System\Core;

use ReflectionClass;
use System\Libs\Hooks;

abstract class Controller extends Database
{
    public function __construct( $modelList )
    {
        $this->_loadModels($modelList);
    }

    protected function _loadModels( $modelList )
    {
        $modelList = array_keys($modelList);
        foreach ( $modelList as $model )
        {
            $model = Model::get($model);
            $refection = new ReflectionClass($model);
            $name = strtolower($refection->getShortName());

            $this->{$name} = $model;
        }
    }

    public function __destruct()
    {
        // Dispatch the later events.
        Hooks::instance()->dispatchPending();
    }

}