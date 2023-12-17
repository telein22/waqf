<?php

namespace Application\Controllers\Admin\Entity;

use Application\Main\EntityController;
use System\Core\Controller;
use Application\Main\AdminController;
use Application\Models\UserSettings;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\File;
use System\Libs\FormValidator;
use System\Responses\View;

class User extends EntityController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $allUsers = $this->user->search('', null, null, 0, null, null, false, null, false, $userInfo['id']);

        $lang = $this->language;

        $view = new View();
        $view->set('Admin/Users/index', [
            'userInfo' => $userInfo,
            'allUsers' => $allUsers,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('users'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function cancellationMembership(Request $request, Response $response)
    {
        $lang = $this->language;
        $entityInfo = $this->user->getInfo();
        $memberId = $request->param(0);
        $member = $this->user->getUser($memberId);

        if ($member['entity_id'] != $entityInfo['id']) {
            $this->session->put('error', $lang('this_user_not_your_member'));
            throw new Redirect("entities/users");
        }

        $this->user->cancelMembership($entityInfo['id'], $member['id']);
        throw new Redirect("entities/users");
    }
}
