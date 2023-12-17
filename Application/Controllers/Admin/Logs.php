<?php

namespace Application\Controllers\Admin;

use Application\Helpers\LogHelper;
use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Logs extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';

        /**
         * @var \Application\Models\Log
         */
        $logsM = Model::get('\Application\Models\Log');
        $logs = $logsM->all( $from, $to );
        $logs = LogHelper::prepare( $logs );

        $view = new View();
        $view->set('Admin/Logs/index', [
            'userInfo' => $userInfo,
            'logs' => $logs,
            'from' => $from,
            'to' => $to,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'System Logs',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}