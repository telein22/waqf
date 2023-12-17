<?php

namespace Application\Controllers;

use Application\Main\AuthController;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Services\HashTag as HashTagService;

class HashTag extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $hashtags = HashTagService::init()->get();

        $view = new View();
        $view->set('HashTag/index', [
            'hashtags' => $hashtags,
        ]);

        $view->prepend('header', [
            'title' => "HashTags"
        ]);
        $view->append('footer');

        $response->set($view);
    }
}