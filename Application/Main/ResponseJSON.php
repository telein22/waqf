<?php

namespace Application\Main;

use System\Core\Exceptions\RenderPages;

class ResponseJSON extends RenderPages
{
    public function __construct( $info, $payload = null )
    {
        $param = array('info' => $info, 'payload' => $payload);
        parent::__construct('JSON', 'index', [$param]);
    }
}