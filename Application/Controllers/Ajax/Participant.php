<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\ParticipantHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Participant extends AuthController
{
    public function list( Request $request, Response $response )
    {
        $entityId = $request->post('eId');
        $entityType = $request->post('eType');

        /**
        * @var \Application\Models\Participant
        */
        $partiM = Model::get('\Application\Models\Participant');
        $data = $partiM->all($entityId, $entityType);
        $data = ParticipantHelper::prepare($data);

        throw new ResponseJSON('success', $data);
    }
}