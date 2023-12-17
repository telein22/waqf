<?php

namespace System\Responses;

use System\Core\IResponse;

class File implements IResponse
{
    private $_contentType;

    private $_data;

    public function __construct( $contentType )
    {
        $this->_contentType = $contentType;
    }

    public function contentType()
    {
        return $this->_contentType;
    }

    public function set( $data )
    {
        $this->_data = $data;
    }

    public function content()
    {
        return $this->_data;   
    }
}