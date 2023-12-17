<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Specialists extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $specialM = Model::get('\Application\Models\Specialty');
        $specialists = $specialM->all();

        $view = new View();
        $view->set('Admin/Specialists/index', [
            'userInfo' => $userInfo,
            'specialists' => $specialists
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Specialists',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function editSpecialty( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $id = $request->param(0);
        $lang = $this->language;

        $formValidator = FormValidator::instance("specialty");
        $formValidator->setRules([
            'specialty_en' => [
                'required' => true,
                'type' => 'string'
            ],
            'specialty_ar' => [
                'required' => true,
                'type' => 'string'
            ],
        ])->setErrors([
            'specialty_en.required' => $lang('field_required'),
            'specialty_ar.required' => $lang('field_required'),
        ]);
        
        $specialM = Model::get('\Application\Models\Specialty');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $specialty_en = $formValidator->getValue('specialty_en');
            $specialty_ar = $formValidator->getValue('specialty_ar');

            $specialM->update($id , [
                'specialty_en' => $specialty_en,
                'specialty_ar' => $specialty_ar,
            ]);

            throw new Redirect("admin/specialties");
        }

        $specialInfo = $specialM->getById( $id );

        $view = new View();
        $view->set('Admin/Specialists/edit_specialist', [
            'userInfo' => $userInfo,
            'specialInfo' => $specialInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Edit Specialist',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function addSpecialty( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $formValidator = FormValidator::instance("specialty");
        $formValidator->setRules([
            'specialty_en' => [
                'required' => true,
                'type' => 'string'
            ],
            'specialty_ar' => [
                'required' => true,
                'type' => 'string'
            ],
        ]);
        $specialM = Model::get('\Application\Models\Specialty');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $specialty_en = $formValidator->getValue('specialty_en');
            $specialty_ar = $formValidator->getValue('specialty_ar');

            $specialM->create([
                'specialty_en' => $specialty_en,
                'specialty_ar' => $specialty_ar,
            ]);
            throw new Redirect("admin/specialties");
        }

        $view = new View();
        $view->set('Admin/Specialists/add_specialist', [
            'userInfo' => $userInfo,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Add Specialists',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}