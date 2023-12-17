<?php

namespace Application\Controllers\Ajax;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Request;
use System\Responses\View;

class Commissions extends AuthController
{
    public function getAdvisorsByEntity(Request $request)
    {
        $entityId = $request->post('entityId');
        $advisors = $this->user->getAssociates($entityId);

        $view = new View();
        $view->set('Admin/Commissions/entity_advisors', [
            'advisors' => $advisors
        ]);

        throw new ResponseJSON('success', $view->content());
    }
}