<?php

namespace Application\Hooks;

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\EarningLog as modelsEarningLog;
use Application\Models\Order;
use Application\Models\Workshop;
use System\Core\Model;

class Log
{
    public function onServiceComplete($data)
    {
        $id = $data['id'];
        $type = $data['type'];
        $data = $data['item'];

        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        switch ($type) 
        {
            case Workshop::ENTITY_TYPE:

                $text = $userInfo['name'] . ' has completed the workshop called ' . $data['name'];

                break;

            case Call::ENTITY_TYPE:
                $creatorInfo = $userM->getUser($data['created_by']);

                $text = $userInfo['name'] . ' has completed the call with ' . $creatorInfo['name'];

                break;

            case Conversation::ENTITY_TYPE:
                $creatorInfo = $userM->getUser($data['created_by']);

                $text = $userInfo['name'] . ' has completed a message request from ' . $creatorInfo['name'];

                break;
        }

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function onServiceCancel($data)
    {
        $id = $data['id'];
        $type = $data['type'];
        $data = $data['item'];

        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();


        switch ($type) 
        {
            case Workshop::ENTITY_TYPE:

                $text = $userInfo['name'] . ' has cancelled the workshop called ' . $data['name'];

                break;

            case Call::ENTITY_TYPE:
                $creatorInfo = $userM->getUser($data['created_by']);

                $text = $userInfo['name'] . ' has cancelled the call with ' . $creatorInfo['name'];

                break;

            case Conversation::ENTITY_TYPE:
                $creatorInfo = $userM->getUser($data['created_by']);

                $text = $userInfo['name'] . ' has cancelled a message request from ' . $creatorInfo['name'];

                break;
        }

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function orderOnCreate($order)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $ownerInfo = $userM->getUser($order['entity_owner_id']);

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:

                $workshopM = Model::get("\Application\Models\Workshop");
                $entityInfo = $workshopM->getInfoById($order['entity_id']);

                $text = $userInfo['name'] . ' has ordered for a new workshop called ' . $entityInfo['name'] . ' from ' . $ownerInfo['name'];

                break;

            case Call::ENTITY_TYPE:

                $callM = Model::get("\Application\Models\Call");
                $entityInfo = $callM->getById($order['entity_id']);

                $text = $userInfo['name'] . ' has booked a call on ' . $entityInfo['date'] . ' from ' . $ownerInfo['name'];

                break;

            case Conversation::ENTITY_TYPE:

                $convoM = Model::get("\Application\Models\Conversation");
                $entityInfo = $convoM->getById($order['entity_id']);

                $text = $userInfo['name'] . ' has requested for a message from ' . $ownerInfo['name'];

                break;
        }

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function onFeedCreate($data)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $arr = json_decode($data['data']['data'], true);

        if (isset($arr['workshop'])) 
        {
            /**
             * @var \Application\Models\Workshop
             */
            $workshopM = Model::get('\Application\Models\Workshop');
            $workshopInfo = $workshopM->getInfoById( $arr['workshop'] );
    
            $text = $userInfo['name'] . ' has created a new workshop called ' . $workshopInfo['name'];
    
            $data = array(
                'user_id' => $userInfo['id'],
                'text' => $text,
                'created_at' => time()
            );
    
            /**
             * @var \Application\Models\Log
             */
            $logM = Model::get('\Application\Models\Log');
            $logM->create($data);
        }
        
    }
    public function onWorkshopCreate($data)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $arr = json_decode( $data['data'], true );
        
        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshopInfo = $workshopM->getInfoById( $arr['workshop'] );

        $text = $userInfo['name'] . ' has created a new workshop called ' . $workshopInfo['name'];

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function orderOnSuccess($order)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $text = $userInfo['name'] . ' has successfully completed his payment for Order Id #' . $order['id'];

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function orderOnAccept($order)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $text = $userInfo['name'] . ' has accepted the Order Id #' . $order['id'];

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }

    public function orderOnReject($order)
    {
        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $text = $userInfo['name'] . ' has rejected the Order Id #' . $order['id'];

        $data = array(
            'user_id' => $userInfo['id'],
            'text' => $text,
            'created_at' => time()
        );

        /**
         * @var \Application\Models\Log
         */
        $logM = Model::get('\Application\Models\Log');
        $logM->create($data);
    }
}
