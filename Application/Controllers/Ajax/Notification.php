<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\NotificationHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed as FeedM;
use Application\Models\Workshop;
use Error;
use System\Core\Config;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\JSON;
use System\Responses\View;

class Notification extends AuthController
{
    public function more(Request $request, Response $response)
    {
        $fromId = $request->post('fromId');
        $fromId = !empty($fromId) ? $fromId : null;
        
        $param = $request->post('param');

        $userInfo = $this->user->getInfo();

        $limit = 10;

        /**
         * @var \Application\Models\Notification
         */
        $notiM = Model::get('\Application\Models\Notification');
        $notifications = $notiM->all($userInfo['id'], $param, $fromId ,0, $limit);
        $notifications = NotificationHelper::prepare($notifications);

        $output = [];
        foreach ($notifications as $notification) {

            $view = new View();
            $view->set('Notification/item', [
                'notification' => $notification,
            ]);

            $output[] = array(
                'notiId' => $notification['id'],
                'noti' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'notis' => $output,
            'dataAvl' => count($notifications) == $limit
        ));
    }
}
