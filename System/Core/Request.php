<?php

namespace System\Core;

class Request extends AbstractInput
{
    use TraitSingle;

    private $_query;

    private $_base;

    private $_uri;

    private $_httpMethod;

    private $_host;

    private $_protocal;

    private $_isAjax;

    private $_httpAgent;

    private function __construct()
    {
        if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) ) return '';

        // Else parse the uri
        // parse_url cant parse url with out http://
        $uri = parse_url("http://dummy" . $_SERVER['REQUEST_URI']);
        $query = isset($uri['query']) ? $uri['query'] : '';
        $uri = isset($uri['path']) ? $uri['path'] : '';
        $base = dirname($_SERVER['SCRIPT_NAME']);
        $base = $base === '/' ? '' : $base;

        // remove and store the base separately.
        if ( $base !== '' && strpos($uri, $base) === 0 )
        {
            $uri = (string) substr($uri, strlen($base));
        }

        $this->_uri = $uri;
        $this->_query = $query;
        $this->_base = $base;

        // Store additional parameters.
        $this->_httpMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'unknown';
        $this->_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $this->_isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->_protocal = stripos($_SERVER['REQUEST_SCHEME'],'https') === 0 ? 'https' : 'http';
        $this->_httpAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    }
    
    public function setParams( $params )
    {
        $this->_params = $params;
    }

    public function param( $index, $default = null )
    {
        return isset($this->_params[$index]) ? $this->_params[$index] : $default;
    }

    public function getHost()
    {
        return $this->_host;
    }

    public function getFullHost()
    {
        return $this->_protocal . '://' . $this->_host . $this->_base;
    }

    public function getProtocal()
    {
        return $this->_protocal;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function getHTTPMethod()
    {
        return $this->_httpMethod;
    }

    public function getUserAgent()
    {
        return $this->_httpAgent;
    }

    public function isAjax()
    {
        return $this->_isAjax;
    }

}