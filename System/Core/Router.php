<?php 

namespace System\Core;

use \System\Core\Exceptions\SystemError;
use \System\Core\Exceptions\RequestError;

class Router {

    private $_request;

    private $_params;

    private $_config;

    private $_route;

    private $_host;

    public function __construct( Request $request )
    {
        $this->_request = $request;
        $this->_config = Config::get('Routes');
        $this->_host = Application::host();
    }

    public function getRoute()
    {
        if ( $this->_route === null ) {
            $uri = $this->_request->getUri();
            $this->_setRoute($uri);
        }

        return $this->_route;
    }

    private function _setRoute( $uri )
    {
        // First get all the routes        
        $all = $this->_config->getAll();
        if ( ! isset($all[$this->_host]) ) throw new SystemError("No routes are set for `{$this->_host}`.");

        $list = $all[$this->_host];
        $uri = $this->_addSlash($uri);
        $this->_matchRoute($list, $uri, '');
    }

    private function _matchRoute( $list, $tomath, $prefix )
    {
        // Now loop through all the registered routes
        foreach ( $list as $key => $v )
        {

            // separate http method from key
            $matches = [];
            $httpmethod = null;
            if ( preg_match('#^(?:([A-z]+):)?(.+)$#', $key, $matches) ) {
                $httpmethod = !empty($matches[1]) ? strtoupper($matches[1]) : $httpmethod;
                $key = !empty($matches[2]) ? $matches[2] : $key;
            }

            // Check if the v is array or not
            // If the v is array then we need to
            // Loop through it
            if ( is_array($v) ) {
                $prefix .= $key;                
                return $this->_matchRoute($v, $tomath, $prefix);
            }

            $matchwith = $prefix .  $key;       
            $key = str_replace(["(:string)", "(:num)"], ["([^/]+)", "([0-9]+)"], $matchwith);
            $matches = [];

            $key = $this->_addSlash($key);

            if ( preg_match('#^' . $key . '$#', $tomath, $matches) )
            {
                if ( $httpmethod !== null && $this->_request->getHTTPMethod() !==  $httpmethod )
                throw new RequestError("Http method is not supported for this uri.");

                // Now check if the action is in valid format.
                if ( !preg_match('#^[A-z0-9]+::[A-z0-9]+$#', $v) )
                    throw new SystemError("Action for route `{$this->_host}{$key}` is not in valid format");

                $action = explode("::", $v);

                unset($matches[0]);
                $this->_route = [];
                $this->_route['action'] = $action[0];
                $this->_route['method'] = $action[1];
                $this->_route['params'] = array_values($matches);

                return true;
            }
        }

        return false;
    }

    private function _addSlash( $uri )
    {
        return substr($uri, strlen($uri) - 1, 1) !== '/' ? $uri .= '/' : $uri;
    }
}