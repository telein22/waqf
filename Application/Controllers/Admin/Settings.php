<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use Application\Models\Settings as ModelsSettings;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Settings extends AdminController
{
    public function index(Request $request, Response $response)
    {
        $userInfo = $this->user->getInfo();
        $lang = $this->language;
        $formValidator = FormValidator::instance("settings");
        $formValidator->setRules([
            'vat' => [
                'required' => true,
                'type' => 'number'
            ],
            'platform_fees' => [
                'required' => true,
                'type' => 'number'
            ]
        ])->setErrors([
            'vat.required' => $lang('field_required'),
            'platform_fees.required' => $lang('field_required'),
        ]);
        $settingM = Model::get('\Application\Models\Settings');
        
        if ( $request->getHTTPMethod() == 'POST' && $isValid = $formValidator->validate() )
        {
            $vat = $formValidator->getValue('vat');
            $platform_fee = $formValidator->getValue('platform_fees');

            $settingM->put(ModelsSettings::KEY_VAT, $vat);
            $settingM->put(ModelsSettings::KEY_PLATFORM_FEES, $platform_fee);
        }


        $vat = $settingM->take(ModelsSettings::KEY_VAT, 0);
        $platform_fees = $settingM->take(ModelsSettings::KEY_PLATFORM_FEES, 0);

        $lang = $this->language;

        $view = new View();
        $view->set('Admin/Settings/index', [
            'userInfo' => $userInfo,
            'vat' => $vat,
            'platform_fees' => $platform_fees,
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => $lang('settings'),
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}
