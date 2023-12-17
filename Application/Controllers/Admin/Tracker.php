<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Tracker extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $from = $request->get('from') ? strtotime($request->get('from') . ' 00:00:00') : '';
        $to = $request->get('to') ? strtotime($request->get('to') . ' 23:59:59') : '';
        
        $trackerM = Model::get('\Application\Models\Tracker');
        $trackers = $trackerM->all( $from, $to );

        $view = new View();
        $view->set('Admin/Tracker/index', [
            'userInfo' => $userInfo,
            'trackers' => $trackers,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'User Tracker',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}