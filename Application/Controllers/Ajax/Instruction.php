<?php

namespace Application\Controllers\Ajax;

use Application\Main\ResponseJSON;
use Application\Models\UserSettings;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;

class Instruction extends Controller
{
    public function skipInstruction( Request $request )
    {
        $userId = $request->post('userId');

        $settingsM = Model::get('\Application\Models\UserSettings');

        $settingsM->put($userId , UserSettings::KEY_SKIP_INSTRUCTION, 1) ;

        throw new ResponseJSON('success', 'Successfully updated');
    }

}