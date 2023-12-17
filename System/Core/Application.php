<?php

namespace System\Core;

use \System\Core\Controller;
use System\Core\Response;
use System\Core\Request;
use System\Core\Router;
use System\Core\Config;
use \System\Core\Exceptions\RequestError;
use \System\Core\Exceptions\SystemError;
use \System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use \System\Core\Exceptions\RenderPages;


class Application
{
    private static $_host;

    private $_request;

    private $_router;

    private $_response;

    public static function config()
    {
        return Config::get('Application');
    }

    public static function isCLI()
    {
        return php_sapi_name() === 'cli';
    }

    public static function host()
    {
        return self::$_host;
    }

    public function __construct()
    {
        $this->_request = Request::instance();

        // Now the request is initialized
        // its time to detect the requested host.
        // if no host is found application will exit.
        $this->_matchApplicationHost();

        // Now create 
        $this->_router = new Router($this->_request);
        $this->_response = new Response();
    }

    public function init()
    {
        // $this->_router->setHost(self::$_host);
        $this->_bootstrap( $this->_router );
    }

    private function _matchApplicationHost()
    {
        $sites = self::config()->site_urls;
        $host = $this->_request->getHost();
        foreach( $sites as $key => $v )
        {
            if ( $v === $host )
            {
                self::$_host = $key;
                return;
            }
        }

        // else no matched host found
        // exit the app
        exit("You are not allowed to access from this domain");
    }

    private function _pathToNamespace( $path )
    {
        $path = preg_replace('#/+#', '/', trim($path));
        if ( substr($path, strlen($path) - 1, strlen($path)) !== '/' ) 
        {
            $path .= '/';
        }

        return str_replace('/', '\\', $path);
    }

    private function _bootstrap( $param )
    {
        try
        {
            $route = $param;

            if ( $param instanceof Router )
            {
                $route = $param->getRoute();
                if ( $route === null ) throw new Error404;
            }
            
            $config = self::config();
            // Working with controller.
            $directory = $config->controller_directory;

            if ( !$directory ) throw new SystemError("Controller directory is not defined.");
            $directory = $this->_pathToNamespace($directory);

            $controller = '\\' . $directory . $route['action'];
            
            if ( !class_exists($controller) ) throw new SystemError("`{$controller}` do not exists.");

            // Now prepare the model list
            $modelList = $config->enable_system_modules;
            $modelList = isset($modelList[self::$_host]) && is_array($modelList[self::$_host]) ?
                $modelList[self::$_host] : [];

            // Now call the controller
            $con = new $controller($modelList);
            if ( ! $con instanceof Controller ) throw new SystemError("`{$controller}` is not valid.");

            $this->_request->setParams($route['params']);
            call_user_func_array(array($con, $route['method']), [$this->_request, $this->_response]);

            if ( ! $this->_response->isRenderPrevented() )
            {
                $this->_response->render();
            }

        }catch ( RenderPages $e )
        {
            $route = array(
                'action' => $e->getAction(),
                'method' => $e->getMethod(),
                'params' => $e->getParams(),           
            );
            $this->_bootstrap( $route );

        } catch ( Redirect $e )
        {
            // Handle the redirection.
            $redirectTo = $e->getTo();
            if ( !preg_match('/^https?:\/\//', $redirectTo) )
                $redirectTo = $this->_request->getFullHost() . '/' . $redirectTo;

            $this->_response->setHeaders("Location: " . $redirectTo);
            $this->_response->setHttpStatus($e->getStatus());

            $this->_response->render();
            exit;

        } catch ( RequestError $e )
        {
            echo $e->getMessage();
        } catch ( SystemError $e )
        {
            echo $e->getMessage() . "\n";
            echo $e->getTraceAsString();
        }

    }

}