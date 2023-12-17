<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\CallHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Call;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class Calls extends AuthController
{
    public function start( Request $request, Response $response )
    {
        $id = $request->post('id');
        if ( empty($id) ) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $call = $callM->getInfoByIds($id);
        if ( empty($call) ) throw new ResponseJSON('error', "Invalid workshop");

        $call = $call[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.start_validation', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();        
        if ( !isset($data['\Application\Hooks\Service::validateStart']) )
            throw new ResponseJSON('error', "Hook not found");        
        $data = $data['\Application\Hooks\Service::validateStart'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data['msg']);

        // Start the call
        $callM->update($id, [
            'status' => Call::STATUS_CURRENT
        ]);

        $this->hooks->dispatch('service.on_start', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();

        throw new ResponseJSON('success');
    }

    public function complete( Request $request, Response $response )
    {
        $id = $request->post('id');
        if ( empty($id) ) throw new ResponseJSON('error', "Invalid entry");

         /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $call = $callM->getInfoByIds($id);
        if ( empty($call) ) throw new ResponseJSON('error', "Invalid call");

        $call = $call[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.complete_validation', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();        
        if ( !isset($data['\Application\Hooks\Service::validateComplete']) )
            throw new ResponseJSON('error', "Hook not found");        
        $data = $data['\Application\Hooks\Service::validateComplete'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data['msg']);

        $callM->update($call['id'], [
            'status' => Call::STATUS_COMPLETED
        ]);

        $this->hooks->dispatch('service.on_complete', [
            'id' => $call['id'],
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->later();

        throw new ResponseJSON('success');
    }

    public function cancel( Request $request, Response $response )
    {
        $id = $request->post('id');  

        if ( empty($id) ) throw new ResponseJSON('error', "Invalid entry");

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $call = $callM->getInfoByIds($id);
        if ( empty($call) ) throw new ResponseJSON('error', "Invalid workshop");

        $call = $call[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.cancel_validation', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();        
        if ( !isset($data['\Application\Hooks\Service::validateCancel']) )
            throw new ResponseJSON('error', "Hook not found");        
        $data = $data['\Application\Hooks\Service::validateCancel'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data['msg']);

       // Start the workshop
        $callM->update($id, [
            'status' => Call::STATUS_CANCELED
        ]);

        $this->hooks->dispatch('service.on_cancel', [
            'id' => $call['id'],
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->later();

        throw new ResponseJSON('success');
    }

    public function join( Request $request, Response $response )
    {
        $id = $request->post('id');
        if ( empty($id) ) throw new ResponseJSON('error', "Invalid entry");

         /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $call = $callM->getInfoByIds($id);
        if ( empty($call) ) throw new ResponseJSON('error', "Invalid Call");

        $call = $call[$id];

        // validate if ready to start
        $data = $this->hooks->dispatch('service.join_validation', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();        
        if ( !isset($data['\Application\Hooks\Service::validateJoin']) )
            throw new ResponseJSON('error', "Hook not found");        
        $data = $data['\Application\Hooks\Service::validateJoin'];

        if ( !$data['isValid'] ) throw new ResponseJSON('error', $data);

        $data = $this->hooks->dispatch('service.on_join', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();   
        // Lets join the workshop the workshop
        // $workM->update($id, [
        //     'status' => ModelsWorkshop::STATUS_CURRENT
        // ]);

        if ( empty($data['\Application\Hooks\Service::onJoin']) )
            throw new ResponseJSON('error', "Hook not found");  
        $data = json_decode($data['\Application\Hooks\Service::onJoin'], true); 

        throw new ResponseJSON('success', $data);
    }

    public function deleteSlot( Request $request )
    {
        $id = $request->post('id');

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');

        $call = $callM->getBySlotId( $id, true );

        if( empty($call) )
        {
            /**
             * @var \Application\Models\CallSlot
             */
            $callSM = Model::get('\Application\Models\CallSlot');
            $callSM->delete($id);

            throw new ResponseJSON('success');
        }

        throw new ResponseJSON('error', "Call already booked");  

    }

    public function searchSlot( Request $request )
    {
        $userId = $request->post('user_id');
        $date = $request->post('date');

        /**
         * @var \Application\Models\CallSlot
         */
        $callSM = Model::get('\Application\Models\CallSlot');
        $slots = $callSM->getSlots($userId, $date, $date);
        $slots = CallHelper::prepareCalender($slots);

        $finalS = [];

        foreach ( $slots as $key => $items )
        {
            foreach ( $items as $item )
            {
                $finalS[] = $item;
            }
        }

        $view = new View();
        $view->set('Profile/call_modal_body', [
            'slots' => $finalS
        ]);

        throw new ResponseJSON('success', $view->content());

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
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $calls = $callM->getList($userInfo['id'], $query, $skip, $limit);
        $calls = CallHelper::prepare($calls, null);

        $output = array();
        foreach ( $calls as $call )
        {
            $view = new View();
            $view->set('Calls/call', [
                'call' => $call,
                'isAdvisor' => $isAdvisor
            ]);
            $output[] = $view->content();
        }

        throw new ResponseJSON('success', array(
            'skip' => $skip + $limit,
            'dataAvl' => count($output) == $limit,
            'calls' => $output,
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
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $calls = $callM->searchOpponents($term, $userInfo['id'], $isAdvisor, 5);

        throw new ResponseJSON('success', $calls);
    }

    public function startAndJoin(Request $request, Response $response)
    {
        // start workflow
        $id = $request->post('id');
        if (empty($id)) {
            throw new ResponseJSON('error', "Invalid entry");
        }

        $callM = Model::get(Call::class);
        $call = $callM->getById($id);
        if (empty($call)) {
            throw new ResponseJSON('error', "Invalid workshop");
        }

        if ($call['status'] == Call::STATUS_NOT_STARTED) {
            // validate if ready to start
            $data = $this->hooks->dispatch('service.start_validation', [
                'type' => Call::ENTITY_TYPE,
                'item' => $call
            ])->now();

            if (!isset($data['\Application\Hooks\Service::validateStart'])) {
                throw new ResponseJSON('error', "Hook not found");
            }

            $data = $data['\Application\Hooks\Service::validateStart'];

            if (!$data['isValid']) {
                throw new ResponseJSON('error', $data);
            }

            // Start the call
            $callM->update($id, [
                'status' => Call::STATUS_CURRENT
            ]);

            $this->hooks->dispatch('service.on_start', [
                'type' => Call::ENTITY_TYPE,
                'item' => $call
            ])->now();

            // modify the status to "current"
            $call['status'] = Call::STATUS_CURRENT;
        }

        // join workflow
        // validate if ready to join
        $data = $this->hooks->dispatch('service.join_validation', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();

        if (!isset($data['\Application\Hooks\Service::validateJoin'])) {
            throw new ResponseJSON('error', "Hook not found");
        }

        $data = $data['\Application\Hooks\Service::validateJoin'];

        if (!$data['isValid']) {
            throw new ResponseJSON('error', $data);
        }

        $data = $this->hooks->dispatch('service.on_join', [
            'type' => Call::ENTITY_TYPE,
            'item' => $call
        ])->now();

        if (empty($data['\Application\Hooks\Service::onJoin'])) {
            throw new ResponseJSON('error', "Hook not found");
        }

        $data = json_decode($data['\Application\Hooks\Service::onJoin'], true);
        throw new ResponseJSON('success', $data);
    }
    
}