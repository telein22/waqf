<?php

namespace Application\Controllers\Admin\Entity;

use Application\Helpers\WorkshopHelper;
use Application\Main\EntityController;
use Application\Models\Workshop;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Workshops extends EntityController
{
    public function index( Request $request, Response $response )
    {
        $entityInfo = $this->user->getInfo();

        $lang = $this->language;

        $associates = $this->user->getAssociates($entityInfo['id']);
        $associatesIds = array_column($associates, 'id');
        $workshops = [];

        if (!empty($associatesIds)) {
            $workM = Model::get(Workshop::class);
            $workshops = $workM->getList([
                'user_id' => $associatesIds
            ]);

            $workshops = WorkshopHelper::prepare($workshops);
        }

        $view = new View();
        $view->set('Admin/Workshops/index', [
            'workshops' => $workshops,
            'userInfo' => $entityInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Admin",
            'currentPage' => $lang('workshops'),
            'userInfo' => $entityInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}