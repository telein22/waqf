<?php

namespace Application\Hooks;

use System\Core\Model;
use System\Helpers\URL;
use Application\Models\Email as ModelsEmail;
use Application\Models\Language;
use Application\Models\UserSettings;

class Email
{
    public function forgetPassword( $data )
    {
        $email = trim($data['email']);
        $otp = trim($data['otp']);

        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getUserByEmail( $email );
        
          /**
         * @var ModelsEmail
         */
        $emailM = Model::get(ModelsEmail::class);
        $mail = $emailM->new(); 
    
        $mail->to([$email, $userInfo['name']]);
        $mail->body('Emails/forgot_password', [
            'otp' => $otp,
            'name' => $userInfo['name'],
            'url' => URL::full('')
        ]);
        $mail->subject("forgot_password");
        $mail->send();
    }

    public function support( $data )
    {
        $email = trim($data['email']);        
    
        /**
         * @var ModelsEmail
         */
        $emailM = Model::get(ModelsEmail::class);
        $mail = $emailM->new(); 

        $mail->to([$email, 'Admin']);
        $mail->body('Emails/support', [
            'content' => $data['content'],
            'url' => URL::full('')
        ]);
        $mail->subject("support_email_title", [ 'email' => $data['content']['sender']['email'] ]);
        $mail->send();
    }

    public function orderReject( $order )
    {
        $userM = Model::get('\Application\Models\User');
        $receiverInfo = $userM->getUser($order['user_id']);
        /**
         * @var Language
         */
        $language = Model::get(Language::class);
        $lang = $language->getUserLang($receiverInfo['id']);

        $paymentM = Model::get('\Application\Models\Payment');
        $order['payment'] = $paymentM->getSuccessPayment( $order['id'] );
        $order['user'] = $receiverInfo;

         /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $order['user']['phone'] = $userSM->take($order['user_id'], UserSettings::KEY_PHONE);
        $country = $userSM->take($order['user_id'], UserSettings::KEY_COUNTRY);
        $city = $userSM->take($order['user_id'], UserSettings::KEY_CITY);

        if ( $country )
        {
            /**
             * @var \Application\Models\Country
             */
            $countryM = Model::get('\Application\Models\Country');
            $order['user']['country'] = $countryM->getById($country);
        }
        
        if ( $city )
        {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $order['user']['city'] = $cityM->getById($city);
        }

        /**
         * @var ModelsEmail
         */
        $emailM = Model::get(ModelsEmail::class);
        $mail = $emailM->new();

        $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
        $mail->body('Emails/' . 'order_rejected', [
            'info' => $order,
            'name' => $receiverInfo['name'],
            'url' => URL::full('')
        ], $lang);
        $mail->subject('order_rejected', null, $lang);
        $mail->send();
    }

}