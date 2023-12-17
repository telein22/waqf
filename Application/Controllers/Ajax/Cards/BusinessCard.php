<?php

namespace Application\Controllers\Ajax\Cards;

use Application\ThirdParties\Whatsapp\Whatsapp as WhatsappService;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use Application\Models\User;

class BusinessCard extends Controller
{
    public function send( Request $request )
    {
        $userInfo = $this->user->getInfo();
        WhatsappService::sendChat($userInfo['phone'], WhatsappMessages::sendBusinessCard($userInfo['name']));

        $data = $request->post('data');
        WhatsappService::sendImageMessage($userInfo['phone'], $data);

        // save the image to Storage
        $base = dirname(dirname(dirname(dirname(__DIR__))));
        $filename = "business_card_{$userInfo['id']}.jpg";
        $data = str_replace('data:image/png;base64,', '', $data);
        file_put_contents("{$base}/Storage/BusinessCards/{$filename}" , base64_decode($data));

        $userM = Model::get(User::class);
        $userM->markBusinessCardASRecieved($userInfo['id']);
    }

}