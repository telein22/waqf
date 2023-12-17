<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\ConversationHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Conversation;
use System\Core\Model;
use System\Core\Request;
use System\Responses\View;

class Messaging extends AuthController
{
    public function book( Request $request )
    {
        $userId = $request->post('user_id');
        $message = $request->post('message');

        $lang = $this->language;

        if ( empty($message) ) throw new ResponseJSON('error', $lang("write_your_message_first"));

        // Message
        $user = $this->user->find(['id' => $userId]);
        if ( empty($user) ) throw new ResponseJSON('error', "Invalid request");

        $userInfo = $this->user->getInfo();

        // Now create a conversation and push to checkout
        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');
        $result = $conM->create([
            'first_message' => $message,
            'owner_id' => $userId,
            'created_by' => $userInfo['id'],
            'date' => date('Y-m-d'),
            'is_temp' => 1,
            'last_message_id' => 0,
            'status' => Conversation::STATUS_CURRENT,
            'created_at' => time()
        ]);

        if ( !$result ) throw new ResponseJSON('error', "Internal server error");

        throw new ResponseJSON('success', [
            'id' => $result
        ]);

    }

    public function more( Request $request )
    {
        $userInfo = $this->user->getInfo();

        $skip = $request->post('skip');
        $limit = $request->post('limit');
        $query = $request->post('query');
        $type = $request->post('type');
        $isAdvisor = $type != 'b';

        if ( !$isAdvisor ) $participant = $userInfo['id'];

        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');
        $conversations = $conM->getList($query, $skip, $limit);
        $conversations = ConversationHelper::prepare($conversations);

        $output = array();
        foreach ( $conversations as $conversation )
        {
            $view = new View();
            $view->set('Messaging/conversation_card', [
                'conversation' => $conversation,
                'isAdvisor' => $isAdvisor
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'skip' => $skip + $limit,
            'dataAvl' => count($output) == $limit,
            'conversations' => $output,
            'isAdvisor' => $isAdvisor
        ));
    }

    public function search( Request $request )
    {
        $term = $request->post('term');
        $type = $request->post('type');

        $isAdvisor = true;
        if ( $type == 'b' ) $isAdvisor = false;
        
        $userInfo = $this->user->getInfo();
        $term = empty($term) ? '' : $term;

        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');
        $conversations = $conM->searchOpponents($term, $userInfo['id'], $isAdvisor, 5);

        throw new ResponseJSON('success', $conversations);
    }
}