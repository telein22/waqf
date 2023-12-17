<?php

namespace Application\Hooks;

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\EarningLog as modelsEarningLog;
use Application\Models\Order;
use Application\Models\Workshop;
use System\Core\Model;

class Ping
{
    public function onComplete()
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getInfo();

        $userM->update( array(
            'lastactive' => time()
        ) , $userInfo['id']);
    }
}