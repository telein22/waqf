<?php


namespace Application\Controllers\Admin;

use Application\Main\AdminController;
use Application\Models\CallSlot;
use Application\Models\Language;
use Application\Models\Workshop;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class LiveSession extends AdminController
{
    public function index( Request $request, Response $response )
    {
        /**
         * @var Language
         */
        $lang = Model::get(Language::class);

        $now = date('Y-m-d H:i:s', time());
        $now2 = date('Y-m-d H:i:s', time() + 60);

        /**
         * @var Workshop
         */
        $workM = Model::get(Workshop::class);
        $workshopNowSlot = $workM->getSlotCountAt($now, $now2);    
        
        /**
         * @var CallSlot
         */
        $callSM = Model::get(CallSlot::class);
        $callNowSlot = $callSM->getSlotCountAt($now, $now2);

        $totalAllowed = Config::get("Website")->max_allowed_concurrent_session;

        $selectedDate = date('Y-m-d');
        $formValidator = FormValidator::instance('filter');
        $formValidator->setRules([
            'date' => [
                'required' => true,
                'type' => 'string'
            ]
        ]);

        if ( $request->getHTTPMethod() == 'POST' && $formValidator->validate() )
        {
            $selectedDate = $formValidator->getValue('date');
        }

        $data = $this->_buildTimeAndData($selectedDate);

        $view = new View();
        $view->set('Admin/LiveSession/index', [
            'workshopNowSlot' => $workshopNowSlot,
            'callNowSlot' => $callNowSlot,
            'totalAllowed' => $totalAllowed,
            'data' => $data
        ]);
        $view->prepend('Admin/header', [
            'currentPage' => $lang('session_tracker'),
            'userInfo' => $this->user->getInfo(),
            'title' => "Welcome to telein",
        ]);
        $view->append('Admin/footer');

        $response->set($view);
    }

    private function _buildTimeAndData( $selectedDate )
    {
        $startTime = $selectedDate . ' 00:00:00';
        $endTime = $selectedDate . ' 23:59:59';

        $start = strtotime($startTime);
        $end = strtotime($endTime);

        $interval = 30 * 60; // 30 minutes

        $times = [];

        /**
         * @var Workshop
         */
        $workM = Model::get(Workshop::class);   

        /**
         * @var CallSlot
         */
        $callSM = Model::get(CallSlot::class);
        $totalAllowed = Config::get("Website")->max_allowed_concurrent_session;

        while ( $start <= $end )
        {
            $time1 = $selectedDate . ' ' . date('H:i:s', $start);
            $time2 = $selectedDate . ' ' . date('H:i:s', $start + $interval - 1);
            $w = $workM->getSlotCountAt($time1, $time2);
            $c = $callSM->getSlotCountAt($time1, $time2);

            $times[] = [
                'from' => $time1,
                'to' => $time2,
                'workshop' => $w,
                'call' => $c,
                'free' => $totalAllowed - ($w + $c)
            ];

            $start += $interval;
        }

        return $times;
    }
}