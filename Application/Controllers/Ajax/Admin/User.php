<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;

class User extends AuthController
{
    public function block( Request $request )
    {
        $id = $request->post('id');

        $data = array(
            'suspended' => 1
        );

        $this->user->update($data, $id);

        throw new ResponseJSON('success', 'Successfully blocked');
    }
    public function unblock( Request $request )
    {
        $id = $request->post('id');

        $data = array(
            'suspended' => 0
        );

        $this->user->update($data, $id);

        throw new ResponseJSON('success', 'Successfully unblocked');
    }
}