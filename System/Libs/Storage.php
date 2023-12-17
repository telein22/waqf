<?php

namespace System\Libs;

use System\Core\Config;
use System\Core\Exceptions\SystemError;
use System\Core\TraitSingle;

class Storage
{

    private $_root;

    private $_file;

    private function _isWritable( $path )
    {
        return is_dir($path) && is_writable($path);
    }

    public function __construct( $root )
    {
        if ( !$this->_isWritable($root) )
            throw new SystemError(
                "Framework storage root is not writable: $root"
            );

        $this->_root = $root;
        $this->_file = new File();
    }

    public function getRoot()
    {
        return $this->_root;
    }

    public function put( $file, $content )
    {
        $path = $this->_getFilePath($file);
        if ( !is_dir($dir = dirname($path)) ) {
            mkdir($dir);
        }

        return file_put_contents($path, $content);
    }

    public function get( $file )
    {
        return $this->has($file) ? new File($this->_getFilePath( $file )) : null;
    }

    public function has( $file )
    {
        return file_exists($this->_getFilePath($file));
    }

    public function lastModified( $file )
    {
        return $this->_filecallback($file, 'lastModified');
    }

    private function _filecallback( $path, $method )
    {
        $this->_file->set($this->_root . DS . $path);
        return call_user_func(array($this->_file, $method));
    }

    public function _getFilePath( $file )
    {
        return $this->_root . DS . $file;
    }

}
