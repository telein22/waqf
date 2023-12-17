<?php

namespace System\Libs;

use Error;
use System\Libs\Optimizer\Decoder;
use System\Libs\Optimizer\Encoder;
use System\Libs\Optimizer\Parameters;

class Optimizer
{
    /**
     * @var \System\Libs\Optimizer\AbstructOptimizer
     */
    private $_object;

    private $_stroge;

    private $_imageDriver = 'gd';

    /**
     * @var \System\Libs\File
     */
    private $_file;

    public function set( $filepath, $driver = null )
    {
        $this->_file = new File();
        $this->_file->set($filepath);
        $mime = $this->_file->getMime();

        if ( !$this->_file->isValid() )
            throw new Error("Invalid file is provided");
        
        $decoder = new Decoder( $filepath, $mime );
        $decoder->decode($driver);
        $this->_object = $decoder->getObject();

        return $this;
    }

    public function optimize( $params )
    {
        $parameters = new Parameters($params);
        $this->_object->optimize( $parameters );

        return $this;
    }

    public function save( $to )
    {
        $pathInfo = pathinfo($to);
        $ext = isset($pathInfo['extension']) ? $pathInfo['extension'] : null;

        $result = file_put_contents($to, $this->raw($ext));

        return $result !== false;
    }

    public function raw( $type = null )
    {
        $type = $type ? $type : $this->_file->getExt();
        $encoder = new Encoder();        
        return $encoder->encode($this->_object, $type);
    }
}

// resize, crop, orient