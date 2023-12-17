<?php

namespace Application\Controllers\Admin;

use Application\Helpers\WorkshopHelper;
use Application\Main\AdminController;
use Application\Models\Workshop;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Workshops extends AdminController
{
    public function index( Request $request, Response $response ) 
    {
        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        /**
         * @var Workshop
         */
        $workM = Model::get(Workshop::class);
        $workshops = $workM->getList([]);
        $workshops = WorkshopHelper::prepare($workshops);

        $view = new View();
        $view->set('Admin/Workshops/index', [
            'workshops' => $workshops,
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Admin",
            'currentPage' => $lang('workshops'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}