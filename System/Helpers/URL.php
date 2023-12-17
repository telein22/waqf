<?php

namespace System\Helpers;

use System\Core\Application;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\Libs\AutoOptimizer;

class URL
{
    public static function siteUrl()
    {
        return Request::instance()->getFullHost();
    }

    public static function current()
    {
        return self::siteUrl() . Request::instance()->getUri();
    }

    public static function full( $uri )
    {
        if ( substr($uri, 0, 1) !== '/' )
        {
            $uri = '/' . $uri;
        }

        return self::siteUrl() . $uri;
    }

    public static function asset( $path )
    {
        $config = Application::config();
        return self::full($path . '?' . $config->version );
    }

    public static function media( $path, $params = null )
    {
        $config = Application::config();
        // $config->enable_auto_optimize
        if ( $config->enable_auto_optimize )
        {
            /**
             * @var \System\Models\AutoOptimizer
             */
            $autoOptimizer = Model::get("\System\Models\AutoOptimizer");
            return $autoOptimizer->setFile(ABS_PATH . DS . $path)->setParams($params)->link();
        }

        // Else return normal one
        return self::full($path . '?' . '123');
    }

    public static function _autoOptimier( $path )
    { 
        /**
         * @var \System\Models\AutoOptimizer
         */
        $autoOptimizer = Model::get('\System\Models\AutoOptimizer');
        $autoOptimizer->setFile($path);

        echo $autoOptimizer->optimize();
    }
}