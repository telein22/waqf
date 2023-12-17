<?php

namespace Application\Controllers\Admin;

use System\Core\Controller;
use Application\Main\AdminController;
use Application\Main\ResponseJSON;
use System\Core\Config;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\View;

class SearchHistory extends AdminController
{
    public function index( Request $request, Response $response )
    {   
        $userInfo = $this->user->getInfo();
        
        $from = $request->get('from') ? strtotime($request->get('from')) : '';
        $to = $request->get('to') ? strtotime($request->get('to')) : '';
        
        $historyM = Model::get('\Application\Models\SearchHistory');
        $allHistory = $historyM->all( $from, $to );
        
        $view = new View();
        $view->set('Admin/SearchHistory/index', [
            'userInfo' => $userInfo,
            'allHistory' => $allHistory,
            'from' => $from,
            'to' => $to
        ]);
        $view->prepend('Admin/header', [
            'title' => "Welcome to telein",
            'currentPage' => 'Search History',
            'userInfo' => $userInfo
        ]);
        $view->append('Admin/footer');
        
        $response->set($view);
    }
}