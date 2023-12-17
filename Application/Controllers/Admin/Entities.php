<?php

namespace Application\Controllers\Admin;

use Application\Helpers\FollowerHelper;
use Application\Main\AdminController;
use Application\Models\User;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Entities extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $userM = Model::get(User::class);
        $entities = $userM->getEntitiesWithAssociatesCount();

        $view = new View();
        $view->set('Admin/Entities/index', [
            'userInfo' => $userInfo,
            'entities' => $entities
        ]);

        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Entities',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function showAssociates(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $entityId = $request->param('0');

        $userM = Model::get(User::class);
        $associates = $userM->getAssociates($entityId, 0, FollowerHelper::PAGE_LIMIT, true);

        $view = new View();
        $view->set('Admin/Entities/show_associates', [
            'userInfo' => $userInfo,
            'associates' => $associates
        ]);

        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Entities',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function addEntity(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $lang = $this->language;

        $formValidator = FormValidator::instance("entity");
        $formValidator->setRules([
            'username' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,username',
                'minchar' => 4,
                'maxchar' => 15
            ],
            'name' => [
                'required' => true,
                'type' => 'string'
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,email',
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ],
            'phone' => [
                'required' => false,
                'type' => 'string',
                'unique' => 'users,phone',
                'minchar' => 10,
                'pattern' => '/^\+[0-9]{1,15}$/'
            ]
        ])->setErrors([
            'username.required' => $lang('field_required'),
            'username.unique' => $lang('username_unique'),
            'username.minchar' => $lang('username_limit', ['min' => 4, 'max' => 15]),
            'username.maxchar' => $lang('username_limit', ['min' => 4, 'max' => 15]),
            'name.required' => $lang('field_required'),
            'email.required' => $lang('field_required'),
            'email.unique' => $lang('email_unique'),
            'email.pattern' => $lang('email_pattern'),
        ]);

        $userM = Model::get(User::class);

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $username = $formValidator->getValue('username');
            $name = $formValidator->getValue('name');
            $email = $formValidator->getValue('email');
            $phone = $formValidator->getValue('phone');

            $userM->create([
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'email_verified' => 1,
                'phone' => $phone,
                'password' => password_hash($email, PASSWORD_DEFAULT),
                'type' => User::TYPE_ENTITY,
                'account_verified' => 0,
                'joined_at' => time(),
                'lastactive' => time(),
                'suspended' => 0
            ]);

            throw new Redirect("admin/entities");
        }

        $view = new View();
        $view->set('Admin/Entities/add_entity', [
            'userInfo' => $userInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Add Entity',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

}
