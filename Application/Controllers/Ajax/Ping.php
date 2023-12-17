<?php

namespace Application\Controllers\Ajax;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;

class Ping extends AuthController
{

    public function index( Request $request, Response $response )
    {
        // How we are going to do
        // When ever ping runs ping will fire
        // fetch the data's from backend.
        $collection = new Class($request) {
            

            private $_collections = [];

            private $_values = [];

            public function __construct( Request $request )
            {
                $this->_values = json_decode($request->post('values'), true);
            }

            public function set( $type, $data ) {
                $this->_collections[$type] = [$data];
            }

            public function get() {
                return $this->_collections;
            }

            public function getValues( $key ) {
                return isset($this->_values[$key]) ? $this->_values[$key] : null;
            }

        };

        $this->hooks->dispatch('ping.collect', $collection)->now();
        
        $data = $collection->get();
        $this->hooks->dispatch('ping.onComplete', $collection)->now();

        throw new ResponseJSON('success', $data);
    }
}