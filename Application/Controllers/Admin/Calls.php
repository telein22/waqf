<?php

namespace Application\Controllers\Admin;

use Application\Helpers\CallHelper;
use Application\Main\AdminController;
use Application\Models\Call;
use Application\Models\CallSlot;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Calls extends AdminController
{
    public function slots( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        /**
         * @var CallSlot
         */
        $callsM = Model::get(CallSlot::class);
        $slots = $callsM->all();
        $slots = CallHelper::prepareSlots($slots);

        $view = new View();
        $view->set('Admin/Calls/index', [
            'slots' => $slots,
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Admin",
            'currentPage' => $lang('calls'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}