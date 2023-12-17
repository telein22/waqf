<?php

namespace Application\Controllers\Ajax;

use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\ResponseJSON;
use Application\Models\Email;
use Application\Models\UserVerify;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;

class Auth extends Controller
{
    public function resendOTP( Request $request, Response $response )
    {
        if ( !$this->user->isLoggedIn() ) throw new ResponseJSON('error');

        $userInfo = $this->user->getInfo();

        /**
         * @var UserVerify
         */
        $verifyM = Model::get(UserVerify::class);
        $token = $verifyM->createToken($userInfo['id'], 'email', $userInfo['email']);

        // send the email.
        /**
         * @var Email
         */
        $emailM = Model::get(Email::class);
        $mail = $emailM->new();

        $mail->to([$userInfo['email'], $userInfo['name']]);
        $mail->body('Emails/' . 'verify_account', [
            'otp' => $token,
            'name' => $userInfo['name'],
            'url' => URL::full('')
        ]);
        $mail->subject('verify_account');
        $mail->send();

        if (!empty($userInfo['phone'])) {
            Whatsapp::sendChat($userInfo['phone'],"Hello {$userInfo['name']}
                    
رمز التحقق الخاص بك هو:
Your verification token is as follows:

{$token}

Regards,
TeleIn Team
support@telein.net"
            );
        }

        throw new ResponseJSON('success');
    }
}