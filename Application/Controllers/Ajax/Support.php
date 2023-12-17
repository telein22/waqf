<?php

namespace Application\Controllers\Ajax;

use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\MainController;
use Application\Main\ResponseJSON;
use System\Core\Config;
use System\Core\Request as CoreRequest;

class Support extends MainController
{
    public function send(CoreRequest $request)
    {
        // Contact with us section
        $name = $request->post('name');
        $email = $request->post('email');
        $message = $request->post('message');
        $recaptcha = $request->post('recaptcha');

        if (!$name || !$email || !$message || !$recaptcha) {
            throw new ResponseJSON('error');
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'secret' => Config::get('Application')->Recaptcha['secret'],
                'response' => $recaptcha
            ]
        ]);

        $res = curl_exec($curl);
        $res = json_decode($res, true);
        curl_close($curl);

        if (isset($res['success']) && $res['success'] == true) {
            $message = <<<EOT
رسالة من الدعم الفني .. المرسل: {$name}
الإيميل: {$email}
الرسالة: {$message}
EOT;

            Whatsapp::sendChat(Config::get('Website')->whatsapp_number, $message);
            
            throw new ResponseJSON('success');
        }

        throw new ResponseJSON('error');
    }
}