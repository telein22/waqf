<?php

namespace Application\Controllers;

use Application\Main\AuthController;
use Application\Models\Email;
use Application\Models\Language;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

class Invite extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $users = array();
        $lang = $this->language;
        $userInfo = $this->user->getInfo() ;

        $error = false;

        $formValidator = FormValidator::instance("invite");
        $formValidator->setRules([
            // 'username' => [
            //     'required' => true,
            //     'type' => 'string',
            //     'unique' => 'users,username',
            //     'minchar' => 4,
            //     'maxchar' => 15
            // ],
            'coupon' => [
                'required' => true,
                'type' => 'string'
            ],
            'type' => [
                'required' => true,
                'type' => 'string'
            ],
            'users' => [
                'required' => true,
                'type' => 'string'
            ],
        ])->setErrors(array(
            'coupon.required' => $lang('field_required'),
            'type.required' => $lang('field_required'),
            'users.required' => $lang('field_required'),

        ));

        if ( $request->getHTTPMethod() == 'POST' && $formValidator->validate() )
        {
            $users = $formValidator->getValue('users');
            $type = $formValidator->getValue('type');
            $coupon = $formValidator->getValue('coupon');

            /**
             * @var \Application\Models\Coupons
             */
            $couponM = Model::get("\Application\Models\Coupons");
            $couponInfo = $couponM->getCouponByCode( $coupon );

            if( empty($couponInfo) )
            {
                $formValidator->setError('coupon', $lang("coupon_not_found"));
                $error = true;
            }

            if( !$error && $couponInfo['used'] >= $couponInfo['max_use'] )
            {
                $formValidator->setError('coupon', $lang("coupon_max_limit_reached"));
                $error = true;
            }

            if( !$error && strtotime($couponInfo['expiry']) < time() )
            {
                $formValidator->setError('coupon', $lang("coupon_expired"));
                $error = true;
            }

            if( !$error && $couponInfo['amount'] != 100 )
            {
                $formValidator->setError('coupon', $lang("coupon_100_required"));
                $error = true;
            }


            /**
             * @var Language
             */
            $language = Model::get(Language::class, 'brd');
            $lang = $language->getUserLang($userInfo['id']);

            /**
             * @var Email
             */
            $emailM = Model::get(Email::class, 'brd');
           
            if( !$error )
            {
                $couponM = Model::get("Application\Models\Coupons");
                
                $couponInfo = $couponM->getCouponByCode($coupon);
                foreach( $users as $user )
                {
                    $couponM->update( array(
                        'used' => $couponInfo['used'] + 1),
                         $couponInfo['id'] 
                    ); 
                    $couponInfo['used']++;

                    $userDetails = $this->user->getUserByEmail( $user ) ;
                    $mail = $emailM->new(); 
            
                    $mail->to([$user, $userDetails['name']]);
                    $mail->body('Emails/' . 'invite_coupon', [
                        'couponInfo' => $couponInfo,
                        'type' => $type,
                        'sender' => $userInfo,
                        'name' => $userInfo['name'],
                        'url' => URL::full('')
                    ], $lang);
                    $mail->subject('invited', null , $lang);
                    $mail->send();
                }

                throw new Redirect("invite");
            }

        }


        $view = new View();
        $view->set('Invite/index', array(
            'users' => $users
        ));
        $view->append('footer');
        $view->prepend('header', [
            'title' => "Welcome to telein"
        ]);

        $response->set($view);
    }
}
