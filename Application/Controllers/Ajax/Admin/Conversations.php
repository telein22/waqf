<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Helpers\MessageHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Conversation;
use System\Core\Exceptions\Error404;
use System\Core\Model;
use System\Core\Request;
use System\Responses\View;

class Conversations extends AuthController
{
    public function show( Request $request )
    {
        $id = $request->post('id');

        /**
         * @var \Application\Models\Conversation
         */
        $comM = Model::get('\Application\Models\Conversation');
        $conversation = $comM->getById($id);

        $userInfo = $this->user->getInfo();
        // Find all messages.
        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $messages = $msgM->getMessages($conversation['id']);
        $messages = MessageHelper::prepare( $messages );


        $view = new View();
        $view->set('Admin/Messages/conversation_view', [
            'user' => $userInfo,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);

        throw new ResponseJSON('success', $view->content());
    }
}