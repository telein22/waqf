<?php

namespace System\Libs\Optimizer;

use InvalidArgumentException;
use System\Libs\Optimizer\Image\GDDriver;

class Encoder
{
    public function encode( AbstructOptimizer $object, $ext )   
    {
        if ( $object instanceof Image )
        {
            return $this->_encodeImage( $object, $ext );
        }

        return null;
    }

    private function _encodeImage( Image $object, $type )
    {
        $driver = $object->getDriver();

        ob_start();
        switch( strtolower($type) )
        {
            case 'jpg':
            case 'jpeg':
                $this->_encodeJpg($driver);
                break;
            case 'gif':          
                $this->_encodeGif($driver);
                break;
            case 'png':
                $this->_encodePng($driver);
                break;
            case 'webp':
                $this->_encodeWebp($driver);
                break;
            default:
                throw new InvalidArgumentException(
                    "Only you can save as jpg, png, gif and webp"
                );
        }
        $content = ob_get_clean();
        return $content;
    }

    private function _encodeJpg( $driver )
    {
        return $driver instanceof GDDriver ? imagejpeg($driver->get()) : imagejpeg($driver->get());
    }

    private function _encodeGif( $driver )
    {
        return $driver instanceof GDDriver ? imagegif($driver->get()) : imagegif($driver->get());
    }

    private function _encodePng( $driver )
    {
        return $driver instanceof GDDriver ? imagepng($driver->get()) : imagepng($driver->get());
    }

    private function _encodeWebp( $driver )
    {
        return $driver instanceof GDDriver ? imagewebp($driver->get()) : imagewebp($driver->get());
    }
}