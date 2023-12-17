<?php

namespace Application\Controllers;

use Application\Hooks\Whatsapp;
use Application\Main\AuthController;
use Application\Models\User;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class BusinessCard extends AuthController
{
    public function generate(Request $request, Response $response)
    {
        $userId = $request->get('user_id');
        $userM = Model::get(User::class);
        $user = $userM->getUser($userId);

        Whatsapp::generateQrCode($user['id']);

        $view = new View();
        $view->set('BusinessCard/index', [
            'user' => $user,
        ]);

        $view->append('footer');

        $response->set($view);
    }
}