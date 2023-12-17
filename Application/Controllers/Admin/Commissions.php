<?php

namespace Application\Controllers\Admin;

use Application\Helpers\CommissionHelper;
use Application\Main\AdminController;
use Application\Models\Commission;
use Application\Models\User;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Commissions extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();

        $commissionM = Model::get(Commission::class);
        $commissions = $commissionM->getAll();

        $commissions = CommissionHelper::prepare($commissions);

        $view = new View();
        $view->set('Admin/Commissions/index', [
            'userInfo' => $userInfo,
            'commissions' => $commissions
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Reviews',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function add(Request $request, Response $response)
    {
        $lang = $this->language;
        $userInfo = $this->user->getInfo();
        $rules = [
            'entity_id' => [
                'required' => true,
                'type' => 'number'
            ],
            'advisor_id' => [
                'required' => true,
                'type' => 'number'
            ],
            'entity_commission' => [
                'required' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 100
            ],
            'advisor_commission' => [
                'required' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 100
            ]
        ];

        $formValidator = FormValidator::instance("commission");
        $formValidator->setRules($rules)->setErrors([
            'entity_id.required' => $lang('field_required'),
            'advisor_id.required' => $lang('field_required'),
            'entity_commission.required' => $lang('field_required'),
            'advisor_commission.required' => $lang('field_required'),
            'entity_commission.min' => $lang('min_entity_commission_invalid', [
                'min' => 0
            ]),
            'entity_commission.max' => $lang('max_entity_commission_invalid', [
                'max' => 100
            ]),
            'advisor_commission.min' => $lang('min_advisor_commission_invalid', [
                'min' => 0
            ]),
            'advisor_commission.max' => $lang('max_advisor_commission_invalid', [
                'max' => 100
            ]),
        ]);

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $commissionM = Model::get(Commission::class);
            $commissionM->create([
                'entity_id' => $formValidator->getValue('entity_id'),
                'advisor_id' => $formValidator->getValue('advisor_id'),
                'entity_commission' => $formValidator->getValue('entity_commission'),
                'advisor_commission' => $formValidator->getValue('advisor_commission'),
            ]);

            throw new Redirect('admin/commissions');
        }

        $entities = $this->user->getEntities();

        $view = new View();
        $view->set('Admin/Commissions/add_commission', [
            'entities' => $entities,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Reviews',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        $response->set($view);
    }

    public function edit(Request $request, Response $response)
    {
        $lang = $this->language;
        $commissionId = $request->param(0);
        $commissionM = Model::get(Commission::class);
        $commission = $commissionM->getById($commissionId);

        $rules = [
            'entity_commission' => [
                'required' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 100
            ],
            'advisor_commission' => [
                'required' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 100
            ]
        ];

        $formValidator = FormValidator::instance("commission");
        $formValidator->setRules($rules)->setErrors([
            'entity_commission.required' => $lang('field_required'),
            'advisor_commission.required' => $lang('field_required'),
            'entity_commission.min' => $lang('min_entity_commission_invalid', [
                'min' => 0
            ]),
            'entity_commission.max' => $lang('max_entity_commission_invalid', [
                'max' => 100
            ]),
            'advisor_commission.min' => $lang('min_advisor_commission_invalid', [
                'min' => 0
            ]),
            'advisor_commission.max' => $lang('max_advisor_commission_invalid', [
                'max' => 100
            ]),
        ]);

        if ($request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate()) {
            $commissionM = Model::get(Commission::class);
            $entityCommission = $formValidator->getValue('entity_commission');
            $advisorCommission = $formValidator->getValue('advisor_commission');

            $commissionM->update($commissionId, $entityCommission, $advisorCommission);

            throw new Redirect('admin/commissions');
        }

        $view = new View();
        $view->set('Admin/Commissions/edit_commission', [
            'commission' => $commission
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",

        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}