<?php

namespace Application\Controllers;

use Application\Main\MainController;
use Application\Models\UserSettings;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\File;

class Language extends MainController
{
    public function change( Request $request, Response $response )
    {
        $lang = $request->get('lang');
        $url = $request->get('url');

        switch( $lang )
        {
            case 'en':
            case 'ar':
                break;
            default:
                throw new Redirect('');
        }

        
        // else set the lang in cookie
        $this->language->setCookieLanguage( $lang );
        
        if( $this->user->isLoggedIn() )
        {
            $userInfo = $this->user->getInfo();
            $userSM = Model::get(UserSettings::class);
            $userSM->put($userInfo['id'] , UserSettings::KEY_LANGUAGE, $lang);
        }
        
        if ( !$url ) throw new Redirect('');

        throw new Redirect(base64_decode($url));
    }
}