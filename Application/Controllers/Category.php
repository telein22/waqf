<?php

namespace Application\Controllers;

use Application\Main\AuthController;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Services\Category as CategoryService;

class Category extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $categories = CategoryService::init()->get();

        $view = new View();
        $view->set('Category/index', [
            'categories' => $categories,
        ]);

        $view->prepend('header', [
            'title' => "Categories"
        ]);
        $view->append('footer');

        $response->set($view);
    }
}