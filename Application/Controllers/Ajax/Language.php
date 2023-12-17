<?php

namespace Application\Controllers\Ajax;

use Application\Main\ResponseJSON;
use Application\Models\UserSettings;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;

class Language extends Controller
{
    public function changeLang( Request $request )
    {
        $userId = $request->post('userId');
        $lang = $request->post('lang');

        $settingsM = Model::get('\Application\Models\UserSettings');

        $settingsM->put($userId , UserSettings::KEY_LANGUAGE, $lang);

        $this->language->setCookieLanguage( $lang );

        throw new ResponseJSON('success', 'Successfully updated');
    }

    public function changeLangCookie( Request $request )
    {
        $lang = $request->post('lang');

        throw new ResponseJSON('success', 'Successfully updated');
    }
}