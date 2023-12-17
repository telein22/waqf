<?php

namespace Application\Controllers;

use System\Core\Controller;
use System\Core\Request;
use System\core\Response;
use System\Responses\JSON as ResponsesJSON;

class JSON extends Controller
{
    public function index( Request $request, Response $response )
    {
        $param = $request->param(0);

        $json = new ResponsesJSON();
        $json->set($param);

        $response->set($json);
    }
}