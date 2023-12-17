<?php

namespace Application\Controllers;

use Application\Main\MainController;
use Application\Models\Language;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;
use Application\Models\Email as ModelEmail;
use System\Helpers\URL;

class Test extends MainController
{
    public function index()
    {
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshopInfo = $workshopM->getInfoById( 99 );
        /**
         * @var ModelEmail
         */
        $emailM = Model::get(ModelEmail::class, 'brd');
        $mail = $emailM->new();

        $language = Model::get(Language::class, 'brd');
        $lang = $language->getUserLang(252);

        $mail->to(['nour-badran23@outlook.com', 'Nour']);
        $mail->body('Emails/workshop_reminder', [
            'info' => $workshopInfo,
            'name' => 'Nour',
            'url' => URL::full('')
        ], $lang);
        $mail->subject('Test email', null , $lang);
        $mail->addAttachment('workshop-99.ics');

        $mail->send();

        var_dump('done');
        die();
    }
}