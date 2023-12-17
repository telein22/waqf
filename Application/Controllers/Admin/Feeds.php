<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Feeds extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $allUsers = $this->user->all();

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';

        $lang = $this->language;

        $view = new View();
        $view->set('Admin/Feeds/index', [
            'userInfo' => $userInfo,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('feeds'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}