<?php

namespace Application\Controllers;

use Application\Helpers\CacheHelper;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\User;
use Application\Models\WithdrawalRequest;
use Application\Models\Workshop;
use Application\Services\WalletService;
use System\Core\Config;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Models\Language;

class WaitingRoom extends AuthController
{
    public function session(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $workshopId = $request->param(0);
        $workshopM = Model::get(Workshop::class);
        $whorkshop = $workshopM->getInfoById($workshopId);

        if ($workshopM->canUserAttend($whorkshop)) {
            throw new Redirect('dashboard');
        }

        $view = new View();
        $view->set('WaitingRoom/session', [
            'id' => $whorkshop['id'],
            'name' => $whorkshop['name'],
            'date' => $whorkshop['date'],
            'isAdvisor' => $whorkshop['user_id'] == User::getId()
        ]);

        $view->prepend('header', [
            'title' => $lang('waiting_room')
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function call(Request $request, Response $response)
    {
        $lang = Model::get(Language::class);
        $callId = $request->param(0);
        $callM = Model::get(Call::class);
        $call = $callM->getById($callId);

        if ($callM->canUserAttend($call)) {
            throw new Redirect('dashboard');
        }

        $view = new View();
        $view->set('WaitingRoom/call', [
            'id' => $call['id'],
            'slot_id' => $call['slot_id'],
            'date' => $call['date'],
            'isAdvisor' => $call['owner_id'] == User::getId()
        ]);

        $view->prepend('header', [
            'title' => $lang('waiting_room')
        ]);
        $view->append('footer');

        $response->set($view);
    }
}