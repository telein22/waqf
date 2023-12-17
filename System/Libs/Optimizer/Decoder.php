<?php

namespace System\Libs\Optimizer;

use System\Core\Exceptions\SystemError;
use System\Libs\Optimizer\Image\GDDriver;
use System\Libs\Optimizer\Image\Helper as ImageHelper;

class Decoder
{
    private $_core;

    private $_type;

    private $_file;

    private $_mime;

    private $_imageDriver = 'gd';

    public function __construct( $file, $mime )
    {
        $this->_file = $file;
        $this->_mime = $mime;
    }

    public function decode( $driverType )
    {
        switch ( $this->_mime )
        {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->_type = 'image';
                $this->_core = $this->_createjepg( $driverType );
                break;
            case 'image/png':
            case 'image/x-png':
                $this->_type = 'image';
                $this->_core = $this->_createpng( $driverType );
                break;
            case 'image/gif':
                $this->_type = 'image';
                $this->_core = $this->_creategif( $driverType );
                break;
            case 'image/webp':
            case 'image/x-webp':
                $this->_type = 'image';
                $this->_core = $this->_createwebp( $driverType );
                break;

            case 'text/js':
                $this->_type = 'js';
                $this->_core = file_get_contents($this->_file);
                break;
            case 'text/css':
                $this->_type = 'css';
                $this->_core = file_get_contents($this->_file);
                break;
            default:
                throw new SystemError("Only jpg, png, gif, webp, js, css files are supported.");
        }
    }

    public function is( $type )
    {
        return $this->_type == $type;
    }

    public function getCore()
    {
        return $this->_core;
    }

    public function getObject( $driverType = null )
    {
        if ( $this->_type === 'image' )
        {
            $helper = new ImageHelper($this->_core);

            $driver = strtolower($driverType) === 'imagick' ? new GDDriver($helper) : new GDDriver($helper);

            return new Image($driver);
        }
    }

    public function _createjepg( $driverType )
    {
        return $driverType === 'gd' ? @imagecreatefromjpeg($this->_file) : @imagecreatefromjpeg($this->_file);
    }

    public function _createpng( $driverType )
    {
        return $driverType === 'gd' ? @imagecreatefrompng($this->_file) : @imagecreatefrompng($this->_file);
    }

    public function _creategif( $driverType )
    {
        return $driverType === 'gd' ? @imagecreatefromgif($this->_file) : @imagecreatefromgif($this->_file);
    }

    public function _createwebp( $driverType )
    {
        return $driverType === 'gd' ? @imagecreatefromwebp($this->_file) : @imagecreatefromwebp($this->_file);
    }
}