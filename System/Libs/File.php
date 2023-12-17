<?php

namespace System\Libs;

use finfo;

class File
{
    protected $file;

    protected $_pathInfo;

    protected $_finfo;

    public function set( $file )
    {
        if ( is_array($file) && isset($file['tmp_name']) && isset($file['name']) )
        {
            $this->file = $file['tmp_name'];
            $this->_pathInfo = pathinfo($file['name']);
        }

        if ( is_string($file) )
        {
            $this->file = $file;
            $this->_pathInfo = pathinfo($file); 
        }
    }

    public function isValid()
    {
        return $this->file && is_file($this->file);
    }

    public function get()
    {
        return $this->isValid() ? $this->file : null;
    }

    public function getRelative( $root = '' )
    {
        if ( !$this->isValid() ) return null;

        if ( $root && substr($root, strlen($root) - 1, 1) !== DS ) $root .= '/';

        // else build a remative path;
        return substr($this->file, strlen($root));
    }

    public function getMime()
    {
        return $this->isValid() ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->file) : null;
    }

    public function getExt()
    {
        return $this->isValid() && isset($this->_pathInfo['extension']) ? $this->_pathInfo['extension'] : null;
    }

    public function lastModified()
    {
        return $this->isValid() ? filemtime($this->file) : null;
    }

    public function move( $to )
    {
        if ( $this->copy($to) )
        {
            $this->delete();

            $this->file = $to;
            return true;
        }

        return false;
    }

    public function copy( $to )
    {
        return $this->isValid() ? copy($this->file, $to) : false;
    }

    public function delete()
    {
        return $this->isValid() ? unlink($this->file) : false;
    }

    public function size()
    {
        return $this->isValid() ? filesize($this->file) : false;
    }

}