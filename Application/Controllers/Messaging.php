<?php

namespace Application\Controllers;

use Application\Helpers\ConversationHelper;
use Application\Helpers\DateHelper;
use Application\Main\AuthController;
use Application\Models\Conversation;
use Application\Models\Message;
use Application\Models\UserSettings;
use System\Core\Config;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Responses\View;

class Messaging extends AuthController
{
    public function index( Request $request, Response $response )
    {
        $userInfo = $this->user->getInfo();

        $type = $request->param(0);

        $isAdvisor = true;
        if ( $type == 'b' ) $isAdvisor = false;

        if ( $isAdvisor && !$this->user->canCreateWorkshop() ) throw new Error404();

        $limit = $request->post('request');
        $limit = empty($limit) ? 8 : $limit;

        $ids = $request->post('ids');
        $status = $request->post('status');
        $date = $request->post('date');

        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');

        $query = [ 'is_temp' => 0 ];
        if ( $isAdvisor )
        {
            $query['owner_id'] = $userInfo['id'];
            $participant = null;

        } else {
            $query['created_by'] = $userInfo['id'];
            $participant = $userInfo['id'];
        }

        if ( !empty($ids) ) {
            if ( $isAdvisor ) $query['created_by'] = $ids;
            else $query['owner_id'] = $ids;
        }
        if ( !empty($status) ) $query['status'] = $status;
        if ( !empty($date) ) $query['date'] = $date;

        $conversations = $conM->getList($query, 0, $limit );
        $conversations = ConversationHelper::prepare($conversations);

        /**
         * @var \Application\Models\Charity
         */
        $charityM = Model::get('\Application\Models\Charity');
        $charities = $charityM->all();

        $selectedUsers = [];
        if ( !empty( $ids ) )
        {
            $selectedUsers = $this->user->getInfoByIds($ids);
        }

        $view = new View();
        $view->set('Messaging/index', [
            'charities' => $charities,
            'conversations' => $conversations,
            'limit' => $limit,
            'query' => $query,
            'selectedUsers' => $selectedUsers,
            'isAdvisor' => $isAdvisor
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function manage( Request $request, Response $response )
    {
        if ( !$this->user->canCreateWorkshop() ) throw new Error404();

        $userInfo = $this->user->getInfo();
        $lang = $this->language;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $price = $userSM->take($userInfo['id'], UserSettings::KEY_MESSAGING_PRICE);
        $enable = $userSM->take($userInfo['id'], UserSettings::KEY_MESSAGING_ENABLE, 0);

        $formValidator = FormValidator::instance('manage_price');
        $formValidator->setRules([
            'price' => [
                'required' => true,
                'type' => "number",
                'min' => 0,
            ]
        ])->setErrors([
            'price.required' => $lang('field_required'),
            'price.type' => $lang('messaging_price_should_number'),
            'price.min' => $lang('messaging_price_invalid'),
        ]);

        $isSubmitted = false;

        if ( $request->getHTTPMethod() == 'POST' && $formValidator->validate() )
        {

            $price = $formValidator->getValue('price');
            $enable = $request->post('enable', 0);

            $userSM->put($userInfo['id'], UserSettings::KEY_MESSAGING_PRICE, $price);
            $userSM->put($userInfo['id'], UserSettings::KEY_MESSAGING_ENABLE, $enable);

            $isSubmitted = true;
        }

        $view = new View();
        $view->set('Messaging/manage_price', [
            'enable' => $enable,
            'price' => $price,
            'isSubmitted' => $isSubmitted
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function view( Request $request, Response $response )
    {
        // if ( !$this->user->canCreateWorkshop() ) throw new Error404();
        
        $param = $request->param(0);
        
        // Find conversation for this param
        
        /**
         * @var \Application\Models\Conversation
         */
        $comM = Model::get('\Application\Models\Conversation');
        $conversation = $comM->getById($param); 
        
        $timeout = Config::get('Website')->conversation_timeout;
        $conversation['remaining'] = implode(':', DateHelper::remains($conversation['created_at'] + $timeout ));
        $conversation['expiryTime'] = $conversation['created_at'] + $timeout;

        if ( empty($conversation) ) throw new Error404();
        
        $userInfo = $this->user->getInfo();
        
        // If current is advisor
        $isAdvisor = $conversation['owner_id'] == $userInfo['id'];
        
        // Find the opponent
        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');
        $participants = $partiM->all($conversation['id'], Conversation::ENTITY_TYPE);
        
        $opponent = null;
        foreach ( $participants as $participant )
        {
            if ( $participant['user_id'] == $userInfo['id'] ) continue;
            $opponent = $participant['user_id'];
            break;
        }
        if ( !$opponent ) throw new Error404;

        $opponent = $this->user->getUser($opponent);
        if ( empty($opponent) ) throw new Error404;

        // Find all messages.
        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $messages = $msgM->getMessages($conversation['id']);

        $view = new View();
        $view->set('Messaging/conversation_view', [
            'isAdvisor' => $isAdvisor,
            'user' => $userInfo,
            'opponent' => $opponent,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
        $view->prepend('header');
        $view->append('footer');

        $response->set($view);
    }

    public function submitAnswer( Request $request )
    {
        $msg = $request->post('answer');
        $senderId = $request->post('sender_id');
        $receiverId = $request->post('receiver_id');
        $conversationId = $request->post('conversation_id');

        /**
         * @var \Application\Models\Message
         */
        $msgM = Model::get('\Application\Models\Message');
        $result = $msgM->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'conversation_id' => $conversationId,
            'message' => $msg,
            'created_at' => time()
        ]);

        if ( $result )
        {
            $this->hooks->dispatch('service.on_complete', [
                'id' => $conversationId,
                'type' => Conversation::ENTITY_TYPE,
                'item' => [
                    'sender_id' => $senderId,
                    'msg' => $msg,
                    'message_id' => $result,
                    'receiver_id' => $receiverId,
                    'conversation_id' => $conversationId
                ]
            ])->now();
        }
        

        throw new Redirect('messaging/view/' . $conversationId);
    }
}