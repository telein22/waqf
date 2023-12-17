<?php

namespace Application\Controllers;

use Application\Main\MainController;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Blog extends MainController
{
    public function index(Request $request, Response $response)
    {
        $blog = $request->param(0);

        $view = new View();
        $view->set("Outer/Home/Blogs/blog{$blog}");
        $view->prepend('Outer/header', [
            'title' => "أهلاً بكم في مدونة تيلي إن"
        ]);
        $view->append('Outer/footer');

        $response->set($view);
    }
}