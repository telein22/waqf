<?php

namespace Application\Controllers\Admin;

use Application\Helpers\CommissionHelper;
use Application\Main\AdminController;
use Application\Models\Commission;
use Application\Models\ProfitProceed;
use Application\Models\User;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class ProfitProceeds extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $profitProceedsM = Model::get(ProfitProceed::class);
        $profitProceeds = $profitProceedsM->getAll();

        $view = new View();
        $view->set('Admin/ProfitProceeds/index', [
            'userInfo' => $userInfo,
            'profitProceeds' => $profitProceeds
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Profits Proceed',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}