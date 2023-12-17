<?php

namespace Application\Main;

use Application\Models\RememberToken;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\Models\Cookie;
use System\Models\Session;

class MainController extends Controller
{
    public function __construct( $modelList )
    {
        parent::__construct( $modelList );
        $userM = Model::get("\Application\Models\User");

        if ( !$userM->isLoggedIn() )
        {
            $cookieM = Model::get(Cookie::class);
            $token = $cookieM->getCookie('loginToken');
            if( $token )
            {
                $rememberM = Model::get(RememberToken::class);
                $exists = $rememberM->getByToken( $token );

                if( $exists )
                {
                    $sessionM = Model::get(Session::class);
                    $sessionM->put('user', $exists['user_id']);
                }
            }
        }
        
    }
}
