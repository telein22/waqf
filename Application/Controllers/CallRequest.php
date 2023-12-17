<?php

namespace Application\Controllers;

use Application\Models\CallRequest as CallRequestModel;
use Application\Main\AuthController;
use Application\Models\User;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Responses\View;
use Application\Services\CallService;

class CallRequest extends AuthController
{
    public function handle(Request $request, Response $response)
    {
        $callRequestId = $request->param(0);
        $callRequestModel = Model::get(CallRequestModel::class);
        $callRequest = $callRequestModel->getById($callRequestId);

        if (!$callRequest) throw new Error404();

        $userModel = Model::get(User::class);
        $beneficiary = $userModel->getUser($callRequest['user_id']);
        $advisor = $userModel->getUser($callRequest['advisor_id']);
        $appointments = json_decode($callRequest['preferences'], true);

        if ($request->getHTTPMethod() == 'POST') {
            $callService = new CallService();
            $callService->create($appointments['date1'], $request->post('price1'));
            $callService->create($appointments['date2'], $request->post('price2'));
            $callService->create($appointments['date3'], $request->post('price3'));

            $this->hooks->dispatch('call_request.on_handle', [
                'advisor' => $advisor,
                'beneficiary' => $beneficiary,
                'callRequest' => $callRequest
            ])->now();

            $this->session->put('call_created', 1);
            throw new Redirect(URL::full('calls/manage'));
        }


        $view = new View();
        $view->set('Calls/request', [
            'beneficiary' => $beneficiary,
            'date1' => $appointments['date1'],
            'date2' => $appointments['date2'],
            'date3' => $appointments['date3'],
        ]);

        $view->prepend('header', [
        ]);

        $view->append('footer');

        $response->set($view);
    }
}