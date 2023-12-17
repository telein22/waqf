<?php

namespace System\Libs\Optimizer;

use System\Libs\Optimizer\Image\IImageDriver;

class Image extends AbstructOptimizer
{
    public function content()
    {
        return $this->_driver->get();
    }

    public function __destruct()
    {
        imagedestroy($this->_driver->get());
    }
}