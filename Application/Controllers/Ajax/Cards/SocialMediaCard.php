<?php

namespace Application\Controllers\Ajax\Cards;

use Application\ThirdParties\Whatsapp\Whatsapp as WhatsappService;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use Application\Models\User;

class SocialMediaCard extends Controller
{
    public function send( Request $request )
    {
        $userInfo = $this->user->getInfo();
        $data = $request->post('data');

        // save the image to Storage
        $base = dirname(dirname(dirname(dirname(__DIR__))));
        $filename = "social_media_card_{$userInfo['id']}.jpg";
        $data = str_replace('data:image/png;base64,', '', $data);
        file_put_contents("{$base}/Storage/SocialMediaCards/{$filename}" , base64_decode($data));
    }

}