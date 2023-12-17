<?php

namespace System\Core\Exceptions;

use System\Core\Application;
use System\Core\Config;

class ExitApp extends \Exception{}
class SystemError extends \Exception {}
class RequestError extends \Exception {}

/**
 * Top redirect exception
 */

class Redirect extends \Exception
{
    private $_to;

    private $_status;

    public function __construct( $to, $status = 200 )
    {
        $this->_to = $to;
        $this->_status = $status;
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}

/**
 * Top Render pages class
 */
class RenderPages extends \Exception
{

    private $_action;

    private $_method;

    private $_params;

    public function __construct( $action, $method, array $params = null )
    {
        $this->_action = $action;
        $this->_method = $method;
        $this->_params = $params;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getParams()
    {
        return is_array($this->_params) ? $this->_params : [];
    }

}

/**
 * 
 */
class Error404 extends RenderPages
{
    public function __construct( array $params = null )
    { 
        // First get the config.
        $config = Application::config();

        $page = $config->page_404;
        if ( ! isset($page['action']) || ! isset($page['method']) ) {
            throw new SystemError(
                "Error 404, Please set an error 404 controller to handle this page.
                See application config for more details."
            );
        }
        
        parent::__construct( $page['action'], $page['method'], $params );
    }
}