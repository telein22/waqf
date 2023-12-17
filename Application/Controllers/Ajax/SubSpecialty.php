<?php

namespace Application\Controllers\Ajax;

use Application\Main\ResponseJSON;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;

class SubSpecialty extends Controller
{
    public function getBySpecialty( Request $request )
    {
        $specialtyIds = $request->post('specialtyId');


        try {
            /**
             * @var \Application\Models\SubSpecialty
             */
            $subSM = Model::get('\Application\Models\SubSpecialty');
            $subSpecialties = $subSM->getBySpecialty($specialtyIds);
        } catch (\Throwable $e)
        {
            var_dump($e->getMessage());
            die();
        }
        throw new ResponseJSON('success', $subSpecialties);
    }
}