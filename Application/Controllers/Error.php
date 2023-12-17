<?php

namespace Application\Controllers;

use Application\Controllers\Admin\SubSpecialists;
use Application\Helpers\FeedHelper;
use Application\Main\AuthController;
use Application\Models\UserSubSpecialty;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Error extends Controller
{
    public function error404( Request $request, Response $response )
    {
        $userInfo = array();
        if( $this->user->isLoggedIn() )
        {
            if ( !$this->user->isVerified() ) throw new Redirect("verify-account");
            $userInfo = $this->user->getInfo();
        }

        $view = new View();
        $view->set('Error/error404', [
            'userInfo' => $userInfo,
        ]);

        if( !empty( $userInfo ) )
        {
            $view->prepend('header', [
                'title' => "Error 404"
            ]);
            $view->append('footer');
        } else 
        {
            $view->prepend('Outer/header', [
                'title' => "Error 404"
            ]);
            $view->append('Outer/footer');
        }

        $response->setHeaders([
            'Status: 404 Not Found',
        ]);
        $response->set($view);
    }
}