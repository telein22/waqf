<?php

namespace Application\Controllers\Ajax;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;

class Location extends AuthController
{
    public function getCities( Request $request )
    {
        $countryId = $request->post('countryId');

        /**
         * @var \Application\Models\City
         */
        $cityM = Model::get('\Application\Models\City');
        $cities = $cityM->getByCountry($countryId);

        throw new ResponseJSON('success', $cities);
    }
}