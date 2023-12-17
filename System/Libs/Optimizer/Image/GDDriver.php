<?php

namespace System\Libs\Optimizer\Image;

use InvalidArgumentException;
use System\Core\Exceptions\SystemError;
use System\Libs\Optimizer\IDriver;

class GDDriver implements IImageDriver, IDriver
{

    private $_helper;

    public function __construct( Helper $helper )
    {
        if ( !extension_loaded('gd') || !function_exists('gd_info') )
        {
            throw new SystemError("GD not supported for this php installation.");
        }

        $this->_helper = $helper;
    }

    public function command_resize($w = null, $h = null, $maintainAspect = true )
    {
        $resizedParams = $this->_helper->resize($w, $h, $maintainAspect);

        $this->_modify(
            0,
            0,
            0,
            0,
            $resizedParams['width'],
            $resizedParams['height'],
            $this->_helper->getWidth(),
            $this->_helper->getHeight()
        );

        return $this;
    }

    public function command_crop($w, $h, $x = null, $y = null)
    {
        list($cX, $cY) = $this->_helper->crop($w, $h, $x, $y);        

        $this->_modify(
            0,
            0,
            $cX,
            $cY,
            $w,
            $h,
            $w,
            $h
        );

        return $this;
    }

    public function command_fit($w, $h = null)
    {
        list($resize) = $this->_helper->fit($w, $h);
        list($rW, $rH) = $resize;

        $this->command_resize($rW, $rH, true)->command_crop($w, $h);

        return $this;
    }

    public function command_rotate( $deg )
    {

    }
    
    public function command_blur( $amount )
    {
        if ( !is_int($amount) )
            throw new InvalidArgumentException(
                "Please enter a blur amount between 0 to 100"
            );

        $amount = min($amount, 100);
        $amount = max(0, $amount);

        for( $i = 0; $i < $amount; $i++ )
        {
            imagefilter($this->_helper->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }
        
        return $this;
    }

    public function command_flip( $direction )
    {
        // TODO: rotate has bug if I dont pass any thing still its matched with 'h'
        switch( $direction )
        {
            case 'h':
                $direction = IMG_FLIP_HORIZONTAL;
                break;
            case 'v':
                $direction = IMG_FLIP_VERTICAL;
                break;
            default:
                $direction = IMG_FLIP_BOTH;
        }

        // var_dump($direction, true == 'h');exit;

        imageflip($this->_helper->getCore(), $direction);

        return $this;
    }

    public function get()
    {
        return $this->_helper->getCore();   
    }

    private function _modify( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h )
    {
        $core = $this->_helper->getCore();

        $image = imagecreatetruecolor($dst_w, $dst_h);

        // first preserve transparency
        $transIndex = imagecolortransparent($core);
        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($image, $transIndex);
            $transColor = imagecolorallocatealpha($image, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($image, 0, 0, $transColor);
            imagecolortransparent($image, $transColor);
        } else {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $result = imagecopyresampled(
            $image,
            $core,
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $dst_w,
            $dst_h,
            $src_w,
            $src_h
        );

        $this->_helper->setCore($image);
    }
}