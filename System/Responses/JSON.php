<?php

namespace System\Responses;

use System\Core\IResponse;

class JSON implements IResponse
{
    private $_data;

    public function contentType()
    {
        return "application/json";
    }

    public function set( $data )
    {
        $this->_data = $data;
    }

    public function content()    
    {
        return json_encode($this->_data);
    }
}