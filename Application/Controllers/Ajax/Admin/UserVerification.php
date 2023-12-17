<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;

class UserVerification extends AuthController
{
    public function index(Request $request)
    {
        $userId = $request->post('userId');
        $type = $request->post('type');

        $userInfo = $this->user->find(array('id' => $userId));

        if ($type == 'account') {
            $verify = $userInfo['account_verified'] == 1 ? 0 : 1;

            $this->user->update(array(
                'account_verified' => $verify
            ), $userId);
        }

        if ($type == 'email') {
            $verify = $userInfo['email_verified'] == 1 ? 0 : 1;

            $this->user->update(array(
                'email_verified' => $verify
            ), $userId);
        }

        throw new ResponseJSON('success', 'Successfully updated');
    }
}
