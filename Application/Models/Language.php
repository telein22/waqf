<?php

namespace Application\Models;

use System\Core\Application;
use System\Core\Model;
use System\Core\Request;
use System\Models\Language as SystemLang;

class Language extends SystemLang
{
    private $_cookieKey = 'lang';
    private $_expireCookie = 30 * 24 * 60 * 60;

    public function __construct( $options = null)
    {
        parent::__construct($options);        

        if ( ! Application::isCLI() )
        {
            $this->_setIfLoggedIn();
            $this->_setIfLoggedOut();
        }        
    }

    public function setCookieLanguage( $lang )
    {
        /**
         * @var \System\Models\Cookie
         */
        $cookieM = Model::get('\System\Models\Cookie');
        $cookieM->setCookie($this->_cookieKey, $lang, time() + $this->_expireCookie, "/");

    }
    
    public function getUserLang( $id )
    {
        /**
         * @var \Application\Models\UserSettings
         */
        $settingsM = Model::get('\Application\Models\UserSettings');
        return $settingsM->take( $id, UserSettings::KEY_LANGUAGE, $this->current());
    }

    private function _setIfLoggedIn()
    {
        $userM = Model::get('\Application\Models\User');
        if ( !$userM->isLoggedIn() ) return;

        $userInfo = $userM->getInfo();

        $userLang = $this->getUserLang( $userInfo['id'] );

        if ( $userLang ) $this->setDefault($userLang);
    }

    private function _setIfLoggedOut()
    {
        $userM = Model::get('\Application\Models\User');
        if ( $userM->isLoggedIn() ) return;

        /**
         * @var \System\Models\Cookie
         */
        $cookieM = Model::get('\System\Models\Cookie');
        // var_dump($cookieM->getCookie($this->_cookieKey));exit;
        if ( $lang = $cookieM->getCookie( $this->_cookieKey ) ) $this->setDefault( $lang );
    }

}