<?php

namespace Application\Controllers\Admin;

use Application\Helpers\SubSpecialtyHelper;
use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class SubSpecialists extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $specialM = Model::get('\Application\Models\SubSpecialty');
        $specialists = $specialM->all();
        $specialists = SubSpecialtyHelper::prepare( $specialists );

        $view = new View();
        $view->set('Admin/SubSpecialists/index', [
            'userInfo' => $userInfo,
            'specialists' => $specialists
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Sub Specialists',
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

        $formValidator = FormValidator::instance("sub_specialty");
        $formValidator->setRules([
            'specialty_en' => [
                'required' => true,
                'type' => 'string'
            ],
            'specialty_ar' => [
                'required' => true,
                'type' => 'string'
            ],
            'special_id' => [
                'required' => true,
                'type' => 'string'
            ],
        ])->setErrors([
            'specialty_en.required' => $lang('field_required'),
            'specialty_ar.required' => $lang('field_required'),
            'special_id.required' => $lang('field_required'),
        ]);

        $subSpecialM = Model::get('\Application\Models\SubSpecialty');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $specialty_en = $formValidator->getValue('specialty_en');
            $specialty_ar = $formValidator->getValue('specialty_ar');
            $special_id = $formValidator->getValue('special_id');

            $subSpecialM->update($id , [
                'specialty_en' => $specialty_en,
                'specialty_ar' => $specialty_ar,
                'special_id' => $special_id
            ]);

            throw new Redirect("admin/sub-specialties");
        }

        $specialInfo = $subSpecialM->getById( $id );

        $specialM = Model::get('\Application\Models\Specialty');
        $allSpecialties = $specialM->all();

        $view = new View();
        $view->set('Admin/SubSpecialists/edit_sub_specialist', [
            'userInfo' => $userInfo,
            'specialInfo' => $specialInfo,
            'allSpecialties' => $allSpecialties
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Edit Sub Specialist',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function addSpecialty( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $lang = $this->language;

        $formValidator = FormValidator::instance("sub_specialty");
        $formValidator->setRules([
            'specialty_en' => [
                'required' => true,
                'type' => 'string'
            ],
            'specialty_ar' => [
                'required' => true,
                'type' => 'string'
            ],
            'special_id' => [
                'required' => true,
                'type' => 'string'
            ],
        ])->setErrors([
            'specialty_en.required' => $lang('field_required'),
            'specialty_ar.required' => $lang('field_required'),
            'special_id.required' => $lang('field_required'),
        ]);
        
        $subSpecialM = Model::get('\Application\Models\SubSpecialty');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $specialty_en = $formValidator->getValue('specialty_en');
            $specialty_ar = $formValidator->getValue('specialty_ar');
            $special_id = $formValidator->getValue('special_id');

            $subSpecialM->create([
                'specialty_en' => $specialty_en,
                'specialty_ar' => $specialty_ar,
                'special_id' => $special_id
            ]);
            throw new Redirect("admin/sub-specialties");
        }

        $specialM = Model::get('\Application\Models\Specialty');
        $allSpecialties = $specialM->all();

        $view = new View();
        $view->set('Admin/SubSpecialists/add_sub_specialist', [
            'userInfo' => $userInfo,
            'allSpecialties' => $allSpecialties
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Add Sub Specialists',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}