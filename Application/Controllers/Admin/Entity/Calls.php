<?php

namespace Application\Controllers\Admin\Entity;

use Application\Helpers\CallHelper;
use Application\Main\AdminController;
use Application\Main\EntityController;
use Application\Models\Call;
use Application\Models\CallSlot;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Calls extends EntityController
{
    public function slots( Request $request, Response $response )
    {
        $entityInfo = $this->user->getInfo();
        $lang = $this->language;

        $associates = $this->user->getAssociates($entityInfo['id']);
        $associatesIds = array_column($associates, 'id');
        $slots = [];

        if (!empty($associatesIds)) {
            $callsM = Model::get(CallSlot::class);
            $slots = $callsM->all([
                'user_id' => $associatesIds,
            ]);
            $slots = CallHelper::prepareSlots($slots);
        }

        $view = new View();
        $view->set('Admin/Calls/index', [
            'slots' => $slots,
            'userInfo' => $entityInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Admin",
            'currentPage' => $lang('calls'),
            'userInfo' => $entityInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}