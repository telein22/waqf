<?php

namespace Application\Controllers\Ajax\Cards;

use Application\ThirdParties\Whatsapp\Whatsapp as WhatsappService;
use System\Core\Controller;
use System\Core\Model;
use Application\Models\User;
use System\Core\Request;

class WorkshopPoster extends Controller
{
    public function send( Request $request )
    {
        $userInfo = $this->user->getInfo();
        $data = $request->post('data');
        WhatsappService::sendImageMessage($userInfo['phone'], $data);
    }

}