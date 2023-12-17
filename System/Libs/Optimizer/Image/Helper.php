<?php

namespace System\Libs\Optimizer\Image;

use InvalidArgumentException;
use System\Libs\File;

class Helper
{
    private $_core;

    private $_width;

    private $_height;

    public function __construct( $core )
    {
        $this->setCore($core);
    }

    public function setCore( $core )
    {
        $this->_core = $core;

        $this->_width = imagesx($core);
        $this->_height = imagesy($core);
    }

    public function getCore()
    {
        return $this->_core;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function getAspectRatio()
    {
        return $this->getWidth() / $this->getHeight();
    }

    public function resize( $w, $h, $maintainAspect )
    {
        if ( !is_int($w) && !is_int($h) )
        {
            throw new InvalidArgumentException(
                "Width or height need to be an proper integer"
            );
        }

        $output = array(
            'width' => is_int($w) ? $w : $this->getWidth(),
            'height' => is_int($h) ? $h : $this->getHeight()
        );

        if ( $maintainAspect )
        {           
            if ( (int) $w > (int) $h )
            {
                // first try with width dominance
                $output['width'] = $w;
                $output['height'] = round($w / $this->getAspectRatio());

                if ( $output['height'] > $h )
                {
                    $output['height'] = $h;
                    $output['width'] = round($h * $this->getAspectRatio());
                }

            } else {
                // Then try with height dominance
                $output['height'] = $h;
                $output['width'] = round($h * $this->getAspectRatio());

                if ( $output['width'] > $w )
                {
                    $output['width'] = $w;
                    $output['height'] = round($w / $this->getAspectRatio());
                }
            }
        }

        return $output;

    }

    public function crop( $w, $h, $x, $y )
    {
        if ( !is_int($w) || !is_int($h) )
        {
            throw new InvalidArgumentException(
                "Width or height is not valid."
            );
        }

        $cX = $x ? $x : 0;
        $cY = $y ? $y : 0;

        if ( is_null($x) && is_null($y) )
        {
            list( $x, $y ) = $this->_align($w, $h, 'center');
            list( $mX, $mY ) = $this->_align($this->_width, $this->_height, 'center');
            $cX = $mX - $x;
            $cY = $mY - $y;
        }

        return [$cX, $cY];

    }

    public function fit( $w, $h )
    {
        if ( !is_int($w) )
        {
            throw new InvalidArgumentException(
                "Invalid argument for width"
            );
        }

        $h = !is_int($h) ? $w : $h;

        $pW = $w;
        $pH = round($w / $this->getAspectRatio());

        if ( $pH < $h )
        {
            $pH = $h;
            $pW = round($h * $this->getAspectRatio());
        }

        return [[$pW, $pH]];

    }

    public function getType( $to )
    {
        // first get the path info.
        // based on that detect the image type.
        $pathInfo = pathinfo($to);

        switch ( strtolower($pathInfo['extension']) )
        {
            case 'jpg':
            case 'jpeg':
                
            case 'webp':
        }
        var_dump($pathInfo);
    }
    
    private function _align($w, $h, $position, $offset_x = 0, $offset_y = 0)
    {
        switch (strtolower($position))
        {
            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $x = intval($w / 2);
                $y = 0 + $offset_y;
                break;

            case 'top-right':
            case 'right-top':
                $x = $w - $offset_x;
                $y = 0 + $offset_y;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $x = 0 + $offset_x;
                $y = intval($h / 2);
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $x = $w - $offset_x;
                $y = intval($h / 2);
                break;

            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $offset_x;
                $y = $h - $offset_y;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $x = intval($w / 2);
                $y = $h - $offset_y;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $x = $w - $offset_x;
                $y = $h - $offset_y;
                break;

            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $x = intval($w / 2) + $offset_x;
                $y = intval($h / 2) + $offset_y;
                break;

            default:
            case 'top-left':
            case 'left-top':
                $x = 0 + $offset_x;
                $y = 0 + $offset_y;
                break;
        }

        return [$x, $y];
    }

}