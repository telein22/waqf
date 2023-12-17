<?php

namespace Application\Controllers\Admin;

use Application\Helpers\ConversationHelper;
use System\Core\Controller;
use Application\Main\AdminController;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Messages extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();

        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';
        
        $convoM = Model::get('\Application\Models\Conversation');
        $convos = $convoM->list();
        $convos = ConversationHelper::prepare($convos);

        $view = new View();
        $view->set('Admin/Messages/index', [
            'userInfo' => $userInfo,
            'convos' => $convos,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Messages',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }
}