<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\EarningLogHelper;
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

class EarningLog extends AuthController
{
    public function more(Request $request, Response $response)
    {
        $fromId = $request->post('fromId');
        $fromId = !empty($fromId) ? $fromId : null;
        $userInfo = $this->user->getInfo();
        
        $from = $request->post('from');
        $to = $request->post('to');

        $limit = 10;

         /**
         * @var \Application\Models\EarningLog
         */
        $logM = Model::get('\Application\Models\EarningLog');
        $logs = $logM->all( $userInfo['id'], $from, $to, $fromId, $limit );
        $logs = EarningLogHelper::prepare( $logs );

        $output = [];
        foreach ($logs as $log) {

            $view = new View();
            $view->set('Earning/item', [
                'log' => $log,
            ]);

            $output[] = array(
                'logId' => $log['id'],
                'log' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'logs' => $output,
            'dataAvl' => count($logs) == $limit
        ));
    }
}
