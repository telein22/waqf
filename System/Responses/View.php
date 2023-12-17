<?php

namespace System\Responses;

use System\Core\Application;
use System\Core\IResponse;
use System\Core\Exceptions\SystemError;
use System\Core\Database;

class View implements IResponse
{
    private $_views = [];

    private $_shouldParse = true;

    private $_directory;

    protected $_db;

    public static function include( $view, array $options = null )
    {
        $v = new self();
        $v->set($view, $options);

        // We cant parse on include 
        // parent view will be parsing will happen
        $v->disableParse();
        echo $v->content();
    }

    public function __construct()
    {
        $config = Application::config();
        $this->_directory = $config->view_directory;
        $this->_db = Database::get();
    }

    public function set( $view, array $options = null )
    {
        $this->_set($view, $options, 0);        
    }

    public function append( $view, array $options = null )
    {
        $this->_set($view, $options, 1);
    }

    public function prepend( $view, array $options = null )
    {
        $this->_set($view, $options, -1);
    }

    public function disableParse()
    {
        $this->_shouldParse = false;
    }

    public function enableParse()
    {
        $this->_shouldParse = true;
    }

    public function contentType()
    {
        return "text/html";
    }

    public function content()
    {
        $output = '';

        foreach ( $this->_views as $view )
        {
            $path = $this->_getPath($view[0] . '.php');
            if ( !file_exists($path) ) throw new SystemError("View not found at `{$path}`.");
            $options = isset($view[1]) ? $view[1] : [];

            $output .= (function () use($path, $options) {
                extract($options);
                ob_start();
                include $path;
                return ob_get_clean();
            })();
        }

        if ( $this->_shouldParse )
        {
            $tokens = $this->_parse($output);        
            $output = $this->_execute($output, $tokens);
        }

        return $output;
    }

    private function _set( $view, $options, $position )
    {
        switch( $position )
        {
            case 1:
                $this->_views[] = [$view, $options];
                break;
            case -1:
                array_unshift(
                    $this->_views,
                    [$view, $options]
                );
                break;
            default:
                $this->_views = [[$view, $options]];
        }

        $this->_generate = true;
    }

    public function _getPath( $file )
    {
        $directory = $this->_directory;
        if ( !$directory ) throw new SystemError("View directory is not set in configuration.");

        return ABS_PATH . DS . $directory . DS . $file;
    }

    public function _parse( $content )
    {
        $tokens = [
            'defines' => []
        ];

        // First try to parse the define
        $matches = array();
        $result = preg_match_all(
            '/<\s*define\s+([\w]+)[^>]*>([\s\S]*?)<\s*\/\s*define\s*>/i',
            $content, $matches
        );

        // if matches any thing save to view
        if ( $result )
        {
            foreach ( $matches[1] as $k => $vars )
            {
                $tokens['defines'][$vars][] = [
                    $matches[2][$k],
                    $matches[0][$k],
                ];
            }
        }

        return $tokens;
    }

    private function _execute( $content, $tokens )
    {
        // first clear the defines.
        foreach ( $tokens['defines'] as $var => $defines )
        {
            $all = '';
            foreach ( $defines as $define )
            {
                $content = str_replace($define[1], '', $content);
                $all .= trim($define[0]);
            }

            // var_dump('<\s*call\s+(' . $var. ')[^>]*>');

            // create a call
            $content = preg_replace('/<\s*call\s+\b' . $var. '\b[^>]*>/i', $all, $content);
        }

        // remove any other pending calls which is not executed.
        $content = preg_replace('/<\s*call[^>]*>/i', '', $content);

        return $content;
    }

}
