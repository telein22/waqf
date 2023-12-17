<?php

namespace Application\Controllers\Ajax;

use Application\Controllers\Ajax\Admin\Coupon;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Coupons;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use  Application\Values\Coupon as CouponValue;

class Checkout extends AuthController
{
    public function prepare( Request $request )
    {
        $id = $request->post('id');
        $type = $request->post('type');

        
        $data = $this->hooks->dispatch('book_now.on_click', array(
            'id' => $id,
            'type' => $type
            ))->now();

        $data = $data['\Application\Hooks\Service::validateCanBook'];
        
        if (!$data['isValid']) throw new ResponseJSON('error', $data['msg']);
        
        $this->session->put('checkout', [ $id, $type ]);
        throw new ResponseJSON('success');
    }

    public function applyCoupon( Request $request )
    {
        $userInfo = $this->user->getInfo();
        $coupon = $request->post('coupon');
        $lang = $this->language;

        if ( empty($coupon) ) throw new ResponseJSON('error', $lang('fill_form'));

        
        /**
         * @var Coupons
         */
       $couponM = Model::get(Coupons::class);
       $coupon = $couponM->getCouponByCode($coupon);

       // Validate coupon
       if ( empty($coupon) || $coupon['deleted'] == 1 ) throw new ResponseJSON('error', $lang('coupon_not_found'));

        @list($entityType, $entityId) = $this->session->take('checkout');
        $result = $couponM->IsUserHasRightToUseTheCoupon(new CouponValue(
            $coupon['code'],
            $coupon['type'],
            $coupon['amount'],
            $coupon['user_id'],
            $coupon['entity_type'],
            $coupon['entity_id']
        ), $entityId, $entityType, $userInfo['id']);

        if (!$result) {
            throw new ResponseJSON('error', $lang('coupon_not_found'));
        }

       if ( $coupon['max_use'] <= $coupon['used'] )  throw new ResponseJSON('error', $lang('coupon_expired'));

       if ( strtotime( $coupon['expiry'] . " 23:59") < time() ) throw new ResponseJSON('error', $lang('coupon_expired'));


       // else add this coupon
       $checkout = $this->session->take('checkout');
       if ( $checkout )
       {
           $checkout[] = $coupon;
           $this->session->put('checkout', $checkout);
       }

       throw new ResponseJSON('success');
    }

    public function removeCoupon( Request $request )
    {
       // else add this coupon
       $checkout = $this->session->take('checkout');
       if ( $checkout )
       {
           $index = count($checkout) - 1;
           $coupon = $checkout[$index];

           if ( is_array($coupon) )  unset($checkout[$index]);

           $this->session->put('checkout', $checkout);
       }

       throw new ResponseJSON('success');
    }
}