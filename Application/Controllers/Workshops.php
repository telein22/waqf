<?php

namespace Application\Controllers;

use Application\Helpers\WorkshopHelper;
use Application\Main\AuthController;
use Application\Models\Call;
use Application\Models\Charity;
use Application\Models\Workshop as WorkshopModel;
use Application\Models\Settings;
use System\Core\Application;
use System\Core\Config;
use System\Core\Database;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Workshops extends AuthController
{
    public function __construct( $modelList )
    {
        $this->_ignore(['workshops/find']);
        parent::__construct($modelList);
    }

    public function index( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $limit = $request->post('request');
        $limit = empty($limit) ? 8 : $limit;

        $names = $request->post('name');
        $status = $request->post('status');
        $date = $request->post('date');

        $workshopM = Model::get(WorkshopModel::class);
        $query = [];

        if ( !empty($names) ) $query['name'] = $names;
        if ( !empty($status) ) $query['status'] = $status;
        if ( !empty($date) ) $query['date'] = $date;

        $workshops = $workshopM->getList($query, 0, $limit, $userInfo['id'] );
        $workshops = WorkshopHelper::prepare($workshops);

        $sM =  Model::get(Settings::class);
        $vat = $sM->take(Settings::KEY_VAT, 0);
        $platform_fees = $sM->take(Settings::KEY_PLATFORM_FEES, 0);

        $view = new View();
        $view->set('Workshop/index', [
            'workshops' => $workshops,
            'limit' => $limit,
            'query' => $query,
            'vat' => $vat,
            'platform_fees' => $platform_fees,
            'user' => $userInfo
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function find( Request $request, Response $response )
    {
        $db = Database::get();
        $lang = $this->language;
        
        $type = $request->get('type', 'all');

        $user = null;

        if( $request->param(0) )
        {
            $user = trim($request->param(0));
            $user = $this->user->getUser($user);

            if( empty($user) ) throw new Redirect('workshops/find');
        }

        $follow = $type == 'follow';

        $limit = 10;

        // $limit = $request->post('request');
        // $limit = empty($limit) ? 10 : $limit;

        $names = $request->post('name');
        // $date = $request->post('date');

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');

        if( !empty($user) )
        {
            $workshops = $workshopM->findUserWorkshops($user['id'], 0, $limit, null , $follow);
        } else {

            if (!$this->user->isLoggedIn()) {
                throw new Redirect("login");
            }

            $userInfo = $this->user->getInfo();
            $workshops = $workshopM->findForBooking($userInfo['id'], 0, $limit, null, $follow, $names);
        }

        $workshops = WorkshopHelper::prepare($workshops);

         /**
         * @var \Application\Models\Settings
         */
        $sM =  Model::get('\Application\Models\Settings');
        $vat = $sM->take(Settings::KEY_VAT, 0);
        $platform_fees = $sM->take(Settings::KEY_PLATFORM_FEES, 0);

        $view = new View();
        $view->set('Workshop/find', [
            'workshops' => $workshops,
            'user' => $user,
            'limit' => $limit,
            'type' => $type,
            'follow' => $follow,
            'vat' => $vat,
            'platform_fees' => $platform_fees,
            'names' => $names
        ]);
        if ( $this->user->isLoggedIn() )
        {
            $view->prepend('header', [
                'title' => $lang('find_workshops')
            ]);
            $view->append('footer');
        } else {
            $view->prepend('Outer/header', [
                'title' => $lang('find_workshops')
            ]);
            $view->append('Outer/footer');
        }
        

        $response->set($view);
    }

    public function checkout( Request $request, Response $response )
    {

        $bookingInfo = $this->session->take('bookingInfo');
        if ( empty($bookingInfo['id']) || empty($bookingInfo['type']) ) throw new Redirect('dashboard');

        $view = new View();
        $view->set('Checkout/prepare_page', [
            'id' => $bookingInfo['id'],
            'type' => $bookingInfo['type']
        ]);
        $view->append('footer');
        $view->prepend('header');

        $response->set($view);
    }
}