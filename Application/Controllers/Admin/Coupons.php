<?php

namespace Application\Controllers\Admin;

use Application\Helpers\OrderHelper;
use Application\Models\Workshop;
use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Coupons extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $couponsM = Model::get('\Application\Models\Coupons');
        $coupons = $couponsM->all();

        $lang = $this->language;

        $view = new View();
        $view->set('Admin/Coupons/index', [
            'userInfo' => $userInfo,
            'coupons' => $coupons
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('coupons'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function uses( Request $request, Response $response )
    {   
        $lang = $this->language;
        $userInfo = $this->user->getInfo();

        $code = $request->param(0);

        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orders = $orderM->getOrdersByCoupon( $code );
        $orders = OrderHelper::prepare($orders);

        $view = new View();
        $view->set('Admin/Coupons/uses', [
            'userInfo' => $userInfo,
            'orders' => $orders
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('coupon_code') . ' - <strong>' . $code . '</strong>',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function editCoupon( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $id = $request->param(0);

        $lang = $this->language;

        $formValidator = FormValidator::instance("coupon");
        $formValidator->setRules([
            'user_id' => [
                'required' => false,
                'type' => 'string'
            ],
            'workshop_id' => [
                'required' => false,
                'type' => 'string'
            ],
            'type' => [
                'required' => true,
                'type' => 'string'
            ],
            'amount' => [
                'required' => true,
                'type' => 'number'
            ],
            'code' => [
                'required' => true,
                'type' => 'string'
            ], 
            'expiry' => [
                'required' => true,
                'type' => 'string'
            ],
            'max_use' => [
                'required' => true,
                'type' => 'number'
            ],
        ])->setErrors([
            'type.required' => $lang('field_required'),
            'amount.required' => $lang('field_required'),
            'code.required' => $lang('field_required'),
            'expiry.required' => $lang('field_required'),
            'max_use.required' => $lang('field_required'),
        ]);

        /**
         * @var \Application\Models\Coupons
         */
        $couponsM = Model::get('\Application\Models\Coupons');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $type = $formValidator->getValue('type') == 'fixed' ? 0 : $formValidator->getValue('type');
            $amount = $formValidator->getValue('amount');
            $code = $formValidator->getValue('code');
            $expiry = $formValidator->getValue('expiry');
            $max_use = $formValidator->getValue('max_use');
            $userId = $formValidator->getValue('user_id');
            $workshopId = $formValidator->getValue('workshop_id');

            $coupon = $couponsM->getCoupon($id);

            $isValid = true;
            if ( $coupon['max_used'] > $coupon['used'] ) {
                $isValid = false;
                $formValidator->setError('max_use', $lang('max_use_cant_not_be_more_than_used')); 
            }

            if ( $isValid )
            {
                $couponsM->update([
                    'type' => $type,
                    'amount' => $amount,
                    'code' => $code,
                    'expiry' => $expiry,                
                    'max_use' => $max_use,
                    'user_id' => $userId == 0 ? null : $userId,
                    'entity_type' => $workshopId == 0 ? null : Workshop::ENTITY_TYPE,
                    'entity_id' => $workshopId == 0 ? null : $workshopId
                ], $id);
    
                throw new Redirect("admin/coupons");
            }
            
        }

        $couponInfo = $couponsM->getCoupon( $id );
        $users = $this->user->getVerifiedUsers();
        $workshopM = Model::get(Workshop::class);
        $workshops = $workshopM->getAllCurrentOrNotStartedWorkshops();

        $view = new View();
        $view->set('Admin/Coupons/edit_coupon', [
            'userInfo' => $userInfo,
            'users' => $users,
            'workshops' => $workshops,
            'couponInfo' => $couponInfo
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('coupons'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    public function addCoupon( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        $formValidator = FormValidator::instance("coupon");
        $formValidator->setRules([
            'user_id' => [
                'required' => false,
                'type' => 'string'
            ],
            'workshop_id' => [
                'required' => false,
                'type' => 'string'
            ],
            'type' => [
                'required' => true,
                'type' => 'string'
            ],
            'amount' => [
                'required' => true,
                'type' => 'number'
            ],
            'code' => [
                'required' => true,
                'type' => 'string'
            ],
            'expiry' => [
                'required' => true,
                'type' => 'string'
            ],
            'max_use' => [
                'required' => true,
                'type' => 'number'
            ],
        ])->setErrors([
            'type.required' => $lang('field_required'),
            'amount.required' => $lang('field_required'),
            'code.required' => $lang('field_required'),
            'expiry.required' => $lang('field_required'),
            'max_use.required' => $lang('field_required'),
        ]);

        $couponsM = Model::get('\Application\Models\Coupons');

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $type = $formValidator->getValue('type') == 'fixed' ? 0 : $formValidator->getValue('type');
            $amount = $formValidator->getValue('amount');
            $code = $formValidator->getValue('code');
            $expiry = $formValidator->getValue('expiry');
            $max_use = $formValidator->getValue('max_use');
            $userId = $formValidator->getValue('user_id');
            $workshopId = $formValidator->getValue('workshop_id');

            $couponsM->create([
                'user_id' => $userId == 0 ? null : $userId,
                'entity_type' => $workshopId == 0 ? null : Workshop::ENTITY_TYPE,
                'entity_id' => $workshopId == 0 ? null : $workshopId,
                'type' => $type,
                'amount' => $amount,
                'code' => $code,
                'expiry' => $expiry,
                'used' => 0,
                'max_use' => $max_use,
                'created_by' => $userInfo['id']
            ]);

            throw new Redirect("admin/coupons");
        }

        $users = $this->user->getVerifiedUsers();
        $workshopM = Model::get(Workshop::class);
        $workshops = $workshopM->getAllCurrentOrNotStartedWorkshops();


        $view = new View();
        $view->set('Admin/Coupons/add_coupon', [
            'userInfo' => $userInfo,
            'users' => $users,
            'workshops' => $workshops
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' =>  $lang('add_coupon'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}