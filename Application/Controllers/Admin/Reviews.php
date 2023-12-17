<?php

namespace Application\Controllers\Admin;

use Application\Helpers\ReviewHelper;
use System\Core\Controller;
use Application\Main\AdminController;
use Application\Main\ResponseJSON;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Workshop;
use System\Core\Config;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\View;

class Reviews extends AdminController
{

    public function index( Request $request, Response $response )
    {   
        $param = $request->param(0);

        switch( $param )
        {
            case Workshop::ENTITY_TYPE:
            case Call::ENTITY_TYPE:
            case Conversation::ENTITY_TYPE:
                break;
            default:
                throw new Error404;
        }

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\Reviews
         */
        $reviewsM = Model::get('\Application\Models\Reviews');
        $reviews = $reviewsM->listAvgRatingsByType( $param );        
        $reviews = ReviewHelper::prepare( $reviews );
        
        $view = new View();
        $view->set('Admin/Reviews/index', [
            'userInfo' => $userInfo,
            'reviews' => $reviews
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Reviews',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        
        $response->set($view);
    }

    public function view( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        $entityId = $request->param(0);
        $entityType = $request->param(1);

        /**
         * @var \Application\Models\Reviews
         */
        $reviewsM = Model::get('\Application\Models\Reviews');
        $reviews = $reviewsM->listByEntity( $entityId, $entityType );        
        $reviews = ReviewHelper::prepare( $reviews );
        
        $view = new View();
        $view->set('Admin/Reviews/view', [
            'userInfo' => $userInfo,
            'reviews' => $reviews
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Workshop Reviews',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        
        $response->set($view);
    }
}