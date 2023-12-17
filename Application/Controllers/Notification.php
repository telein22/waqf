<?php

namespace Application\Controllers;

use Application\Helpers\NotificationHelper;
use Application\Main\AuthController;
use Application\Models\Notification as ModelsNotification;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Notification extends AuthController
{
    public function index( Request $request, Response $response )
    {
        $param = $request->param(0);
        switch( $param )
        {
            case ModelsNotification::TYPE_SOCIAL:
            case ModelsNotification::TYPE_SERVICE:
                break;
            default:
                throw new Error404;
        }

        $lang = $this->language;

        $userInfo = $this->user->getInfo();

        $limit = 10;

        /**
         * @var \Application\Models\Notification
         */
        $notiM = Model::get('\Application\Models\Notification');
        $notifications = $notiM->all($userInfo['id'], $param, null ,0, $limit);
        $notifications = NotificationHelper::prepare($notifications);

        $notiM->updateRead( $userInfo['id'], $param );

        $view = new View();
        $view->set('Notification/index', [
            'notifications' => $notifications,
            'limit' => $limit,
            'param' => $param
        ]);
        $view->prepend('header', [
            'title' => $lang('notifications')
        ]);
        $view->append('footer');

        $response->set($view);
    }
}