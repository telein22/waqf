<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;

class Coupon extends AuthController
{
    public function delete( Request $request )
    {
        $id = $request->post('id');

        $couponsM = Model::get('\Application\Models\Coupons');
        $couponsM->deleteById($id);

        throw new ResponseJSON('success', 'Successfully deleted');
    }
}