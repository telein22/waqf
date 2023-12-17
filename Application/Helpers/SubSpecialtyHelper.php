<?php

namespace Application\Helpers;

use Application\Models\Expression;
use Application\Models\Notification as ModelsNotification;
use System\Core\Model;
use System\Helpers\URL;

class SubSpecialtyHelper
{
    public static function prepare($SubSpecialists)
    {
        foreach ($SubSpecialists as &$SubSpecialist) 
        {
            $specialIds[$SubSpecialist['special_id']] = $SubSpecialist['special_id'];
        }
        
        /**
         * @var \Application\Models\User
         */
        $specialM = Model::get('\Application\Models\Specialty');
        $specialists = $specialM->getInfoByIds($specialIds);
        
        foreach( $SubSpecialists as &$SubSpecialist )
        {
            $SubSpecialist['specialInfo'] = isset($specialists[$SubSpecialist['special_id']]) ? $specialists[$SubSpecialist['special_id']] : null;
        }

        return $SubSpecialists;
    }
}