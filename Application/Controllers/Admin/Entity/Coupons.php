<?php

namespace Application\Controllers\Admin\Entity;

use Application\Helpers\OrderHelper;
use Application\Main\EntityController;
use Application\Main\ResponseJSON;
use Application\Models\Workshop;
use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use Application\Models\Coupons as CouponMode;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Coupons extends EntityController
{
    public function index( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $couponsM = Model::get(CouponMode::class);
        $coupons = $couponsM->all([
            'created_by' => $userInfo['id']
        ]);

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
            'amount' => [
                'required' => true,
                'type' => 'number',
                'max' => 100
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
            'amount.required' => $lang('field_required'),
            'amount.max' => $lang('amount_cannot_be_above_100'),
            'code.required' => $lang('field_required'),
            'expiry.required' => $lang('field_required'),
            'max_use.required' => $lang('field_required'),
        ]);

        $couponsM = Model::get(CouponMode::class);

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $amount = $formValidator->getValue('amount');
            $code = $formValidator->getValue('code');
            $expiry = $formValidator->getValue('expiry');
            $max_use = $formValidator->getValue('max_use');

            $coupon = $couponsM->getCoupon($id);

            $isValid = true;
            if ( $coupon['max_used'] > $coupon['used'] ) {
                $isValid = false;
                $formValidator->setError('max_use', $lang('max_use_cant_not_be_more_than_used'));
            }

            if ( $isValid )
            {
                $couponsM->update([
                    'type' => CouponMode::TYPE_PERCENT,
                    'amount' => $amount,
                    'code' => $code,
                    'expiry' => $expiry,
                    'max_use' => $max_use,
                ], $id);

                throw new Redirect("entities/coupons");
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
            'amount' => [
                'required' => true,
                'type' => 'number',
                'max' => 100
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
            'amount.required' => $lang('field_required'),
            'amount.max' => $lang('amount_cannot_be_above_100'),
            'code.required' => $lang('field_required'),
            'expiry.required' => $lang('field_required'),
            'max_use.required' => $lang('field_required'),
        ]);

        $couponsM = Model::get(CouponMode::class);

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $amount = $formValidator->getValue('amount');
            $code = $formValidator->getValue('code');
            $expiry = $formValidator->getValue('expiry');
            $max_use = $formValidator->getValue('max_use');

            $couponsM->create([
                'type' => CouponMode::TYPE_PERCENT,
                'amount' => $amount,
                'code' => $code,
                'expiry' => $expiry,
                'used' => 0,
                'max_use' => $max_use,
                'created_by' => $userInfo['id']
            ]);

            throw new Redirect("entities/coupons");
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