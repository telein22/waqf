<?php

namespace System\Libs\Optimizer\Image;

interface IImageDriver
{
    public function __construct( Helper $helper );

    public function command_resize( $w = null, $h = null, $maintainAspect = true );

    public function command_crop( $w, $h, $x = null, $y = null );

    public function command_fit( $w, $h = null );

    public function command_rotate( $deg );

    public function command_blur( $amount );

    public function command_flip( $flip  );
}