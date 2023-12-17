<?php

namespace Application\Controllers;

use Application\Main\MainController;
use Application\Models\Language;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Terms extends MainController
{
    public function index( Request $request, Response $response )
    {   
        $view = new View();

        $lang = Model::get(Language::class);
        $currentLang =  $lang->current();

        switch ( $currentLang ) {
            case 'ar':
                $setView = 'Outer/Terms/index_ar';
                break;
            
            default:
                $setView = 'Outer/Terms/index_en';
                break;
        }

        $view->set($setView);
        $view->prepend('Outer/header', [
            'title' => "Welcome to telein"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }
}