<?php

namespace Application\Controllers;

use Application\Main\AuthController;
use Application\Main\MainController;
use Application\Models\Language;
use Application\Models\UserSettings;
use Exception;
use System\Core\Config;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

class Settings extends AuthController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $settingsM = Model::get('\Application\Models\UserSettings');

        /**
         * @var Language
         */
        $lang = Model::get(Language::class);

        $formValidator = FormValidator::instance("settings");
        $formValidator->setRules([
            'msg' => [
                'required' => true,
                'type' => 'string'
            ],
            'type' => [
                'required' => true,
                'type' => 'select',
                'values' => [1, 2]
            ]
        ])->setErrors([
            'msg.required' => $lang('field_required'),
            'type.required' => $lang('field_required')
        ]);

        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $msg = $formValidator->getValue('msg');
            $type = $formValidator->getValue('type');

            
            $config = Config::get("Website");            
            

            if ( $type == 1 ) {
                $number = $config->whatsapp_number;
                throw new Redirect("https://api.whatsapp.com/send/?phone=" . $number . "&text=". urldecode($msg) . "&app_absent=0");
            }

            $email = $config->admin_email;
            $data = array(
                'email' => $email,
                'content' => [
                    'sender' => $userInfo,
                    'msg' => $msg
                ]
            );

            $this->hooks->dispatch('settings.on_submit_support', $data)->later();
            
        }

        $userLang = $settingsM->take( $userInfo['id'], UserSettings::KEY_LANGUAGE);

        $view = new View();
        $view->set('Settings/index', array(
            'userInfo' => $userInfo,
            'userLang' => $userLang
        ));
        $view->prepend('header', [
            'title' => "Welcome to telein"
        ]);
        $view->append('footer');

        $response->set($view);
    }
}