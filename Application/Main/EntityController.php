<?php

namespace Application\Main;

use Application\Models\User;
use System\Core\Controller;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;

class EntityController extends AuthController
{
    public function __construct( $modelList )
    {
        parent::__construct( $modelList );

        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        if($userInfo['type'] != User::TYPE_ENTITY) throw new Redirect('logout');
    }
}
