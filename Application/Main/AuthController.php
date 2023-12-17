<?php

namespace Application\Main;

use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;

class AuthController extends MainController
{
    private $_ignore = [];

    public function __construct( $modelList )
    {
        parent::__construct( $modelList );        

        /**
         * @var Request
         */
        $request = Request::instance();
        $uri = $request->getUri();
        $willIgnore = false;

        foreach ($this->_ignore as $key => $value) {
            if ( strpos($uri, $value) ) $willIgnore = true;
        }
        
        $userM = Model::get("\Application\Models\User");

        if ( !$request->isAjax() )
        {
            if ( !$willIgnore && !$userM->isLoggedIn() ) throw new Redirect("login");
            if ( !$willIgnore || $userM->isLoggedIn() )
            {
                if ( !$userM->isVerified() ) throw new Redirect("verify-account");
                if ( $userM->isBlocked() ) throw new Redirect("account-blocked");
            }            
        
        }

        // else ajax
        if ( !$willIgnore && !$userM->isLoggedIn() ) throw new ResponseJSON("error", 'login-required');
        
        if ( !$willIgnore || $userM->isLoggedIn() )
        {
            if ( !$userM->isVerified() ) throw new ResponseJSON('error', "unverified-account");
            if ( $userM->isBlocked() ) throw new ResponseJSON('error', "blocked");
        }
        
    }

    protected function _ignore( $uris )
    {
        $this->_ignore = (array) $uris;
    }
    
}
