<?php

namespace Application\Hooks;

use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\AppHelper;
use Application\Helpers\WorkshopHelper;
use Application\Models\Participant;
use Application\Services\WalletService;
use Application\Services\WorkshopService;
use Application\Services\CallService;
use Application\ThirdParties\MeetingProviders\Dyte\DyteProvider;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\Main\ResponseJSON;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use Pusher\Pusher;
use Application\Models\Call;
use Application\Models\CallSlot;
use Application\Models\Charity;
use Application\Models\Conversation;
use Application\Models\EarningLog;
use Application\Models\Email;
use Application\Models\Language;
use Application\Models\Meeting;
use Application\Models\MeetingApi;
use Application\Models\Notification;
use Application\Models\Order;
use Application\Models\Payment;
use Application\Models\Queue;
use Application\Dtos\Workshop as WorkshopDto;
use Application\Dtos\Call as CallDto;
use Application\Dtos\Meeting as MeetingDto;
use Application\Models\Reviews;
use Application\Models\ServiceLog;
use Application\Models\Transfer;
use Application\Models\User;
use Application\Models\Workshop;
use Application\ThirdParties\Firebase\Firebase;
use Application\ThirdParties\MeetingProviders\BigBlueButton\BigBlueButtonProvider;
use Application\ThirdParties\MeetingProviders\Zoom\ZoomProvider;
use System\Core\Application;
use System\Core\Config;
use System\Core\Exceptions\ExitApp;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\Hooks;

class Service
{
    const ADVISOR_NOT_STARTED = 'advisor_not_started';
    const WORKSHOP_EXPIRED = 'workshop_expired';

    public function validateCanBook($dataInfo)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        $data = [
            'isValid' => true,
            'msg' => null
        ];
        switch ($dataInfo['type']) {
            case Workshop::ENTITY_TYPE:


                /**
                 * @var \Application\Models\Workshop
                 */
                $workM = Model::get('\Application\Models\Workshop');
                $workshop = $workM->getInfoByIds($dataInfo['id']);
                if (empty($workshop)) {
                    $data['isValid'] = false;
                    $data['msg'] = $lang('workshop_not_exists');
                    break;
                }
                $workshop = $workshop[$dataInfo['id']];

                // if work shop exists
                // we need to check for slots
                /**
                 * @var \Application\Models\Participant
                 */
                $partiM = Model::get('\Application\Models\Participant');
                $count = $partiM->count($dataInfo['id'], Workshop::ENTITY_TYPE);
                $count = isset($count[$dataInfo['id']]) ? $count[$dataInfo['id']] : 0;

                if ($workshop['capacity'] <= $count) {
                    $data['isValid'] = false;
                    $data['msg'] = $lang('slot_not_available');
                    break;
                }

                break;
        }

        return $data;
    }

    public function validateStart($data)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        $v = array('isValid' => true, 'msg' => '');
        switch ($data['type']) {
            case Workshop::ENTITY_TYPE:

                // first validate the entity starting time.
                // if the entity starting is not working then trow error
                // that time is up
                // if workshop already started then response that workshop already started.
                $will = strtotime($data['item']['date']);
                $currentTime = time();

                $userId = Model::get('\Application\Models\User')->getId();
                if ($userId != $data['item']['user_id']) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('not_service_owner');
                    break;
                }

                $startPadding = $will;
                $endPadding = $will + ($data['item']['duration'] * 60);

                if ($currentTime < $startPadding) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('workshop_time_yet_not_started');
                    break;
                }

                if ($currentTime >= $endPadding) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('service_time_over');
                    break;
                }

                break;
            case Call::ENTITY_TYPE:

                // first validate the entity starting time.
                // if the entity starting is not working then trow error
                // that time is up
                // if workshop already started then response that workshop already started.
                $will = strtotime($data['item']['date']);
                $currentTime = time();

                $userId = Model::get('\Application\Models\User')->getId();
                if ($userId != $data['item']['owner_id']) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('not_service_owner');
                    break;
                }

                $startPadding = $will;
                $endPadding = $will + ($data['item']['duration'] * 60);

                if ($currentTime < $startPadding) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('call_time_yet_not_started');
                    break;
                }

                if ($currentTime >= $endPadding) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('service_time_over');
                    break;
                }

                break;
        }

        return $v;
    }

    public function validateCancel($data)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        $v = array('isValid' => true, 'msg' => '');
        switch ($data['type']) {
            case Workshop::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Order
                 */
                $orderM = Model::get('\Application\Models\Order');
                $orderedBefore = $orderM->ifOrdered($data['item']['id'], Workshop::ENTITY_TYPE);

                if (!$orderedBefore) {
                    $coupon = $data['item']['cancel_coupon'];

                    /**
                     * @var \Application\Models\Coupons
                     */
                    $couponM = Model::get('\Application\Models\Coupons');
                    $coupon = $couponM->getCouponByCode($coupon);

                    // Validate coupon
                    if (empty($coupon)) {
                        $v['isValid'] = false;
                        $v['msg'] = $lang('coupon_not_found');
                        break;
                    }
                    break;
                } else {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('workshop_already_booked');
                    break;
                }
        }

        return $v;
    }

    public function onCancel($data)
    {
        // On service cancel we need to cancel the orders
        // and delete participants

        $id = $data['id'];
        $type = $data['type'];
        $data = $data['item'];

        /**
         * @var Email
         */
        $emailM = Model::get(Email::class);
        $lang = Model::get('\Application\Models\Language');
        switch ($type) {
            case Workshop::ENTITY_TYPE:

                $userId = Model::get('\Application\Models\User')->getId();

                /**
                 * @var \Application\Models\Order
                 */
                $orderM = Model::get('\Application\Models\Order');
                $orders = $orderM->getOrders($userId, Workshop::ENTITY_TYPE, $data['id']);

                foreach ($orders as $order) {
                    $orderM->update($order['id'], [
                        'status' => Order::STATUS_CANCELED,
                        'remark' => 'order_advisor_canceled'
                    ]);

                    /**
                     * @var \Application\Models\Payment
                     */
                    $paymentsM = Model::get("\Application\Models\Payment");
                    $payments = $paymentsM->all($order['id']);

                    foreach ($payments as $payment) {
                        $paymentsM->update($payment['id'], array('status' => Payment::STATUS_REFUND_INITIATED));
                    }
                }

                /*
                * @var \Application\Models\Participant
                */
                $partiM = Model::get('\Application\Models\Participant');
                $participants = $partiM->getByEntities([$type => $id]);

                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get('\Application\Models\Notification');
                foreach ($participants as $participant) {
                    $notiM->create([
                        'sender_id' => $userId,
                        'receiver_id' => $participant['user_id'],
                        'type' => Notification::TYPE_SERVICE,
                        'action_type' => Notification::ACTION_WORKSHOP_CANCELED,
                        'data' => json_encode($data),
                        'read' => 0,
                        'sent' => 0,
                        'created_at' => time(),
                    ]);

                    /**
                     * @var \Application\Models\User
                     */
                    $userM = Model::get("\Application\Models\User");
                    $receiverInfo = $userM->getUser($participant['user_id']);

                    /**
                     * @var Language
                     */
                    $language = Model::get(Language::class);
                    $receiverLang = $language->getUserLang($receiverInfo['id']);

                    $mail = $emailM->new();

                    $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
                    $mail->body('Emails/' . 'workshop_canceled', [
                        'info' => $data,
                        'name' => $receiverInfo['name'],
                        'url' => URL::full('')
                    ], $receiverLang);
                    $mail->subject('workshop_canceled', null, $receiverLang);
                    $mail->send();
                }

                // $orders = $orderM->getOrders(null, Workshop::ENTITY_TYPE, $data['id'], Order::STATUS_APPROVED);

                // $userInfo = $this->user->getInfo();
                // /**
                //  * @var \Application\Models\EarningLog
                //  */
                // $logM = Model::get('\Application\Models\EarningLog');
                // foreach ( $orders as $order )
                // {
                //     $arr = array(
                //         'order_id' => $order['id'],
                //         'text' => 'order_canceled_log'
                //     );

                //     $data = array(
                //         'action_type' => EarningLog::LOG_ORDER_CANCEL,
                //         'data' => json_encode($arr),
                //         'created_at' => time(),
                //         'user_id' => $userInfo['id']
                //     );

                //     $logM->create( $data );
                // }

                break;

            case Call::ENTITY_TYPE:

                $userId = Model::get('\Application\Models\User')->getId();
                $isAdvisor = $userId == $data['owner_id'];

                /**
                 * @var \Application\Models\Order
                 */
                $orderM = Model::get('\Application\Models\Order');
                $orders = $orderM->getOrders($data['owner_id'], Workshop::ENTITY_TYPE, $data['id']);

                foreach ($orders as $order) {
                    $orderM->update($order['id'], [
                        'status' => Order::STATUS_CANCELED,
                        'remark' => 'order_advisor_canceled'
                    ]);

                    /**
                     * @var \Application\Models\Payment
                     */
                    $paymentsM = Model::get("\Application\Models\Payment");
                    $payments = $paymentsM->all($order['id']);

                    foreach ($payments as $payment) {
                        $paymentsM->update($payment['id'], array('status' => Payment::STATUS_REFUND_INITIATED));
                    }
                }

                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get('\Application\Models\Notification');
                $notiM->create([
                    'sender_id' => $isAdvisor ? $data['owner_id'] : $data['created_by'],
                    'receiver_id' => $isAdvisor ? $data['created_by'] : $data['owner_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => Notification::ACTION_CALL_CANCELED,
                    'data' => json_encode($data),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time(),
                ]);

                /**
                 * @var \Application\Models\User
                 */
                $userM = Model::get("\Application\Models\User");
                $receiverInfo = $userM->getUser($isAdvisor ? $data['created_by'] : $data['owner_id']);

                /**
                 * @var Language
                 */
                $language = Model::get(Language::class);
                $receiverLang = $language->getUserLang($receiverInfo['id']);

                $mail = $emailM->new();

                $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
                $mail->body('Emails/' . 'call_canceled', [
                    'info' => $data,
                    'name' => $receiverInfo['name'],
                    'url' => URL::full('')
                ], $receiverLang);
                $mail->subject('call_canceled', null, $receiverLang);
                $mail->send();

                $orders = $orderM->getOrders(null, Call::ENTITY_TYPE, $data['id'], Order::STATUS_APPROVED);

                $userM = Model::get('\Application\Models\User');
                $userInfo = $userM->getInfo();
                /**
                 * @var \Application\Models\EarningLog
                 */
                $logM = Model::get('\Application\Models\EarningLog');
                foreach ($orders as $order) {
                    $arr = array(
                        'text' => 'order_canceled_log'
                    );

                    $logData = array(
                        'entity_id' => $order['id'],
                        'entity_type' => Order::ENTITY_TYPE,
                        'action_type' => EarningLog::LOG_ORDER_CANCEL,
                        'data' => json_encode($arr),
                        'created_at' => time(),
                        'user_id' => $userInfo['id']
                    );

                    $logM->create($logData);
                }

                break;
        }

        // $queue->

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');
        $partiM->delete($type, $id);
    }

    public function validateComplete($data)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        $v = array('isValid' => true, 'msg' => '');
        switch ($data['type']) {
            case Workshop::ENTITY_TYPE:
                break;
        }

        return $v;
    }

    public function onComplete($data)
    {
        // On service cancel we need to cancel the orders
        // and delete participants
        $id = $data['id'];
        $type = $data['type'];
        $data = $data['item'];

        $orderM = Model::get(Order::class);
        $orderM->updateStatusByEntityId($id, $type, Order::STATUS_COMPLETED);

        $emailM = Model::get(Email::class);
        $lang = Model::get(Language::class);

        $userM = Model::get(User::class);
        $userInfo = $userM->getInfo();

        switch ($type) {
            case Workshop::ENTITY_TYPE:
                $workshopM = Model::get(Workshop::class);
                $entityInfo = $workshopM->getInfoById($id);

                $meetingM = Model::get(Meeting::class);
                $meetingInfo = $meetingM->getByEntity($entityInfo['id'], Workshop::ENTITY_TYPE);

                $config = Config::get("Big");

                $meetingData = array(
                    'url' => $config->end_meeting_url,
                    'fields' => [
                        'serverURL' => $config->server_url,
                        'meetingID' => $meetingInfo['meeting_id'],
                    ]
                );

                $meetinApiM = Model::get(MeetingApi::class);
                $result = $meetinApiM->index($meetingData);

                $userId = Model::get(User::class)->getId();

                $orders = $orderM->getOrders(
                    null,
                    Workshop::ENTITY_TYPE,
                    $id,
                    Order::STATUS_COMPLETED
                );

                foreach ($orders as $order) {
                    $paymentM = Model::get(Payment::class);
                    $payments = $paymentM->all($order['id'], Payment::STATUS_SUCCESS);


                    $receiverType = 'advisor';

                    $workshopM = Model::get(Workshop::class);
                    $entityInfo = $workshopM->getInfoById($order['entity_id']);
                    $entityInfo['charity'] = json_decode($entityInfo['charity'], true);
                    $receiverId = $entityInfo['user_id'];

                    if (!empty($entityInfo['charity'])) {
                        $receiverType = Charity::ENTITY_TYPE;
                        $receiverId = $entityInfo['charity'];
                    }

                    $transferM = Model::get(Transfer::class);

                    foreach ($payments as $payment) {
                        if (!empty($entityInfo['charity'])) {
                            $charityAmount = $order['advisor_amount'] / count($entityInfo['charity']);
                            // foreach ($entityInfo['charity'] as $charity) {
                            //     $transferData = array(
                            //         'order_id' => $order['id'],
                            //         'payment_id' => $payment['id'],
                            //         'receiver_type' => Transfer::RECEIVER_CHARITY,
                            //         'receiver_id' => $charity,
                            //         'receiver_amount' => $charityAmount,
                            //         'status' => Transfer::STATUS_TRANSFERRED,
                            //         'created_at' => time()
                            //     );

                            //     $transferM->create($transferData);
                            // }
                        } else {
                            // $transferData = array(
                            //     'order_id' => $order['id'],
                            //     'payment_id' => $payment['id'],
                            //     'receiver_type' => Transfer::RECEIVER_ADVISOR,
                            //     'receiver_id' => $receiverId,
                            //     'receiver_amount' => $order['advisor_amount'],
                            //     'status' => Transfer::STATUS_INITIALIZED,
                            //     'created_at' => time()
                            // );

                            // $transferId = $transferM->create($transferData);

                            // $queueData = array(
                            //     'type' => Queue::TYPE_TRANSFER,
                            //     'data' => json_encode(array('transfer_id' => $transferId)),
                            //     'priority' => 4,
                            //     'created_at' => time()
                            // );
                            // /**
                            //  * @var \Application\Models\Queue
                            //  */
                            // $queueM = Model::get('\Application\Models\Queue');
                            // $queueM->create($queueData);
                        }

                        // $transferData = array(
                        //     'order_id' => $order['id'],
                        //     'payment_id' => $payment['id'],
                        //     'receiver_type' => Transfer::RECEIVER_ADMIN,
                        //     'receiver_id' => 0,
                        //     'receiver_amount' => $order['admin_amount'],
                        //     'status' => Transfer::STATUS_TRANSFERRED,
                        //     'created_at' => time()
                        // );

                        // $transferM->create($transferData);
                    }
                }

                // /**
                //  * @var \Application\Models\Order
                //  */
                // $orderM = Model::get('\Application\Models\Order');
                // $orders = $orderM->getOrders(
                //     null,
                //     Workshop::ENTITY_TYPE,
                //     $id,
                //     Order::STATUS_COMPLETED
                // );       

                // $userM = Model::get('\Application\Models\User');
                // $userInfo = $userM->getInfo();
                // foreach( $orders as $order )
                // {

                //     $arr = array(
                //         'order_id' => $order['id'],
                //         'text' => 'order_completed_log'
                //     );

                //     $data = array(
                //         'action_type' => EarningLog::LOG_ORDER_COMPLETE,
                //         'data' => json_encode($arr),
                //         'created_at' => time(),
                //         'user_id' => $userInfo['id']
                //     );

                //     /**
                //      * @var \Application\Models\EarningLog
                //      */
                //     $logM = Model::get('\Application\Models\EarningLog');
                //     $logM->create( $data );
                // }

                $partiM = Model::get(Participant::class);
                $participants = $partiM->getByEntities([$type => $id]);

                $notiM = Model::get(Notification::class);
                foreach ($participants as $participant) {
                    $notiM->create([
                        'sender_id' => $userId,
                        'receiver_id' => $participant['user_id'],
                        'type' => Notification::TYPE_SERVICE,
                        'action_type' => Notification::ACTION_WORKSHOP_COMPLETED,
                        'data' => json_encode($data),
                        'read' => 0,
                        'sent' => 0,
                        'created_at' => time(),
                    ]);

                    $receiverInfo = $userM->getUser($participant['user_id']);
                    $receiverLang = $lang->getUserLang($participant['user_id']);

                    $mail = $emailM->new();

                    $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
                    $mail->body('Emails/' . 'workshop_completed', [
                        'info' => $data,
                        'name' => $receiverInfo['name'],
                        'url' => URL::full('')
                    ], $receiverLang);
                    $mail->subject('workshop_completed', null, $receiverLang);
                    $mail->send();

                    //send what's app message to all participants

                    $message = WhatsappMessages::confirmCompleteWorkshop($receiverInfo['name'], $data['name'], $id);
                    Whatsapp::sendChat($receiverInfo['phone'], $message);
                }

                break;
            case Call::ENTITY_TYPE:
                $callM = Model::get(Call::class);
                $entityInfo = $callM->getById($id);

                $meetingM = Model::get(Meeting::class);
                $meetingInfo = $meetingM->getByEntity($entityInfo['id'], Call::ENTITY_TYPE);

                $config = Config::get("Big");

                $meetingData = array(
                    'url' => $config->end_meeting_url,
                    'fields' => [
                        'serverURL' => $config->server_url,
                        'meetingID' => $meetingInfo['meeting_id'],
                    ]
                );

                $meetinApiM = Model::get(MeetingApi::class);
                $result = $meetinApiM->index($meetingData);

                $orderM = Model::get(Order::class);
                $orders = $orderM->getOrders(
                    null,
                    Call::ENTITY_TYPE,
                    $id,
                    Order::STATUS_COMPLETED
                );


                foreach ($orders as $order) {

                    $arr = array(
                        'text' => 'order_completed_log'
                    );

                    $logData = array(
                        'entity_id' => $order['id'],
                        'entity_type' => Order::ENTITY_TYPE,
                        'action_type' => EarningLog::LOG_ORDER_COMPLETE,
                        'data' => json_encode($arr),
                        'created_at' => time(),
                        'user_id' => $userInfo['id']
                    );

                    $logM = Model::get(EarningLog::class);
                    $logM->create($logData);
                }

                $userId = Model::get(User::class)->getId();

                $orderM = Model::get(Order::class);
                $orders = $orderM->getOrders(
                    null,
                    Call::ENTITY_TYPE,
                    $id,
                    Order::STATUS_COMPLETED
                );

                foreach ($orders as $order) {
                    $paymentM = Model::get(Payment::class);
                    $payments = $paymentM->all($order['id']);

                    $receiverType = 'advisor';

                    $callM = Model::get(Call::class);
                    $entityInfo = $callM->getById($order['entity_id']);
                    $entityInfo['charity'] = json_decode($entityInfo['charity'], true);
                    $receiverId = $entityInfo['owner_id'];

                    if (!empty($entityInfo['charity'])) {
                        $receiverType = Charity::ENTITY_TYPE;
                        $receiverId = $entityInfo['charity'];
                    }

                    $transferM = Model::get(Transfer::class);

                    foreach ($payments as $payment) {
                        if (!empty($entityInfo['charity'])) {
                            $charityAmount = $order['advisor_amount'] / count($entityInfo['charity']);
                            foreach ($entityInfo['charity'] as $charity) {
                                // $transferData = array(
                                //     'order_id' => $order['id'],
                                //     'payment_id' => $payment['id'],
                                //     'receiver_type' => Transfer::RECEIVER_CHARITY,
                                //     'receiver_id' => $charity,
                                //     'receiver_amount' => $charityAmount,
                                //     'status' => Transfer::STATUS_TRANSFERRED,
                                //     'created_at' => time()
                                // );

                                // $transferM->create($transferData);
                            }
                        } else {
                            // $transferData = array(
                            //     'order_id' => $order['id'],
                            //     'payment_id' => $payment['id'],
                            //     'receiver_type' => Transfer::RECEIVER_ADVISOR,
                            //     'receiver_id' => $receiverId,
                            //     'receiver_amount' => $order['advisor_amount'],
                            //     'status' => Transfer::STATUS_INITIALIZED,
                            //     'created_at' => time()
                            // );

                            // $transferId = $transferM->create($transferData);

                            // $queueData = array(
                            //     'type' => Queue::TYPE_TRANSFER,
                            //     'data' => json_encode(array('transfer_id' => $transferId)),
                            //     'priority' => 4,
                            //     'created_at' => time()
                            // );
                            // /**
                            //  * @var \Application\Models\Queue
                            //  */
                            // $queueM = Model::get('\Application\Models\Queue');
                            // $queueM->create($queueData);
                        }

                        // $transferData = array(
                        //     'order_id' => $order['id'],
                        //     'payment_id' => $payment['id'],
                        //     'receiver_type' => Transfer::RECEIVER_ADMIN,
                        //     'receiver_id' => 0,
                        //     'receiver_amount' => $order['admin_amount'],
                        //     'status' => Transfer::STATUS_TRANSFERRED,
                        //     'created_at' => time()
                        // );

                        // $transferM->create($transferData);
                    }
                }

                $partiM = Model::get(Participant::class);
                $participants = $partiM->getByEntities([$type => $id]);

                $notiM = Model::get(Notification::class);
                foreach ($participants as $participant) {
                    $notiM->create([
                        'sender_id' => $userId,
                        'receiver_id' => $participant['user_id'],
                        'type' => Notification::TYPE_SERVICE,
                        'action_type' => Notification::ACTION_CALL_COMPLETED,
                        'data' => json_encode($data),
                        'read' => 0,
                        'sent' => 0,
                        'created_at' => time(),
                    ]);

                    $receiverInfo = $userM->getUser($data['created_by']);
                    $receiverLang = $lang->getUserLang($data['created_by']);

                    $mail = $emailM->new();

                    $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
                    $mail->body('Emails/' . 'call_completed', [
                        'info' => $data,
                        'name' => $receiverInfo['name'],
                        'url' => URL::full('')
                    ], $receiverLang);
                    $mail->subject('call_completed', null, $receiverLang);
                    $mail->send();


                    //send what's app message to all participants
                    $owner = $userM->getUser($entityInfo['owner_id']);

                    $message = WhatsappMessages::confirmCompleteCall($owner['name'], $receiverInfo['name'], $id);
                    Whatsapp::sendChat($receiverInfo['phone'], $message);

                }


                break;
            case Conversation::ENTITY_TYPE:
                $orderM = Model::get(Order::class);
                $orders = $orderM->getOrders(
                    null,
                    Conversation::ENTITY_TYPE,
                    $id,
                    Order::STATUS_COMPLETED
                );

                foreach ($orders as $order) {

                    $arr = array(
                        'text' => 'order_completed_log'
                    );

                    $logData = array(
                        'entity_id' => $order['id'],
                        'entity_type' => Order::ENTITY_TYPE,
                        'action_type' => EarningLog::LOG_ORDER_COMPLETE,
                        'data' => json_encode($arr),
                        'created_at' => time(),
                        'user_id' => $userInfo['id']
                    );

                    $logM = Model::get(EarningLog::class);
                    $logM->create($logData);
                }

                // First mark this conversation as completed
                $conM = Model::get(Conversation::class);
                $conM->update($id, [
                    'last_message_id' => $data['message_id'],
                    'status' => Conversation::STATUS_COMPLETED
                ]);

                $orderM = Model::get(Order::class);
                $orders = $orderM->getOrders(
                    null,
                    Conversation::ENTITY_TYPE,
                    $id,
                    Order::STATUS_COMPLETED
                );


                foreach ($orders as $order) {
                    $paymentM = Model::get(Payment::class);
                    $payments = $paymentM->all($order['id']);

                    $receiverType = 'advisor';

                    $convoM = Model::get(Conversation::class);
                    $entityInfo = $convoM->getById($order['entity_id']);
                    $receiverId = $entityInfo['owner_id'];

                    /**
                     * @var \Application\Models\Transfer
                     */
                    $transferM = Model::get(Transfer::class);

                    foreach ($payments as $payment) {
                        // $transferData = array(
                        //     'order_id' => $order['id'],
                        //     'payment_id' => $payment['id'],
                        //     'receiver_type' => Transfer::RECEIVER_ADVISOR,
                        //     'receiver_id' => $receiverId,
                        //     'receiver_amount' => $order['advisor_amount'],
                        //     'status' => Transfer::STATUS_INITIALIZED,
                        //     'created_at' => time()
                        // );

                        // $transferId = $transferM->create($transferData);

                        // $queueData = array(
                        //     'type' => Queue::TYPE_TRANSFER,
                        //     'data' => json_encode(array('transfer_id' => $transferId)),
                        //     'priority' => 4,
                        //     'created_at' => time()
                        // );
                        // /**
                        //  * @var \Application\Models\Queue
                        //  */
                        // $queueM = Model::get('\Application\Models\Queue');
                        // $queueM->create($queueData);

                        // $transferData = array(
                        //     'order_id' => $order['id'],
                        //     'payment_id' => $payment['id'],
                        //     'receiver_type' => Transfer::RECEIVER_ADMIN,
                        //     'receiver_id' => 0,
                        //     'receiver_amount' => $order['admin_amount'],
                        //     'status' => Transfer::STATUS_TRANSFERRED,
                        //     'created_at' => time()
                        // );

                        // $transferM->create($transferData);
                    }
                }

                // Also create a notification.
                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get(Notification::class);
                $notiM->create([
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => Notification::ACTION_MESSAGE_COMPLETED,
                    'data' => json_encode($data),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time(),
                ]);

                $receiverInfo = $userM->getUser($data['receiver_id']);
                $receiverLang = $lang->getUserLang($data['receiver_id']);

                $mail = $emailM->new();

                // var_dump($lang);exit;
                $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
                $mail->body('Emails/' . 'conversation_completed', [
                    'info' => $data,
                    'name' => $receiverInfo['name'],
                    'url' => URL::full('')
                ], $receiverLang);
                $mail->subject('conversation_completed', null, $receiverLang);
                $mail->send();

                $owner = $userM->getUser($data['sender_id']);
                $message = WhatsappMessages::confirmReplayToMessage($owner['name'], $receiverInfo['name'], $id);
                Whatsapp::sendChat($receiverInfo['phone'], $message);

                break;
            case Reviews::ENTITY_TYPE:
                // If the review is completed we can fire a trigger.

                break;
        }




         $orderM = Model::get('\Application\Models\Order');
         $orders = $orderM->getOrders(
             null,
             $type,
             $id,
             Order::STATUS_COMPLETED
         );

        foreach ($orders as $order)
        {
            $paymentM = Model::get(Payment::class);
            $payments = $paymentM->all($order['id'], Payment::STATUS_SUCCESS);

            foreach ($payments as $payment) {
                WalletService::addToWallet(
                    new \Application\Dtos\Order(
                        $order['id'],
                        $order['user_id'],
                        $order['amount'],
                        $order['payable'],
                        $order['final_amount'],
                        $order['advisor_amount'],
                        null,
                        $order['entity_owner_id']
                    )
                );
            }
        }



    }

    public function validateJoin($data)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');
        $v = array('isValid' => true, 'msg' => '', 'key' => '');
        switch ($data['type']) {
            case Workshop::ENTITY_TYPE:

                // First check if the workshop is expired.
                $workshop = $data['item'];

                /**
                 * @var \Application\Models\User
                 */
                $userM = Model::get(User::class);
                $userInfo = $userM->getInfo();

                // is advisor.
                $isAdvisor = $workshop['user_id'] == $userInfo['id'];

                if (
                    $workshop['status'] == Workshop::STATUS_COMPLETED ||
                    $workshop['status'] == Workshop::STATUS_CANCELED ||
                    $workshop['status'] == Workshop::STATUS_NOT_STARTED ||
                    $workshop['status'] == Workshop::STATUS_PREPARING
                ) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('workshop_join_' . $workshop['status']);
                    $v['key'] = 'workshop_join_' . $workshop['status'];

                    // if not started and advisor yet to clicked on start button
                    if ($workshop['status'] == Workshop::STATUS_NOT_STARTED && time() > strtotime($workshop['date'])) {
                        $v['msg'] = $lang("service_join_advisor_not_started");
                        $v['key'] = self::ADVISOR_NOT_STARTED;
                    }

                    // if not started and user is not advisor show not started 
                    if ($workshop['status'] == Workshop::STATUS_NOT_STARTED && WorkshopHelper::isExpired($workshop['date'], $workshop['duration'])) {
                        $v['msg'] = $lang("workshop_join_expired");
                        $v['key'] = self::WORKSHOP_EXPIRED;
                    }
                    break;
                } else {
                    $config = Config::get("Big");
                    $wConfig = Config::get("Website");

                    $valid = strtotime($workshop['date']) + (($workshop['duration'] - $wConfig->join_padding) * 60) > time();
                    if (!$valid) {
                        $v['isValid'] = false;
                        $v['msg'] = $lang("workshop_join_expired");
                        $v['key'] = self::WORKSHOP_EXPIRED;
                        break;
                    }


//                    if (!$isAdvisor) {
//
//                        /**
//                         * @var Meeting
//                         */
//                        $meetingM = Model::get(Meeting::class);
//                        $meetingInfo = $meetingM->getByEntity($workshop['id'], Workshop::ENTITY_TYPE);
//
//                        $data = array(
//                            'url' => $config->check_meeting_url,
//                            'fields' => [
//                                'serverURL' => $config->server_url,
//                                'name' => $userInfo['name'],
//                                'meetingID' => $meetingInfo['meeting_id'],
//                                'userID' => $userInfo['id']
//                            ]
//                        );
//
//                        $meetinApiM = Model::get(MeetingApi::class);
//                        $result = $meetinApiM->index($data);
//
//                        $arr = json_decode($result, true);
//
//                        if ($arr['Status'] == 0) {
//                            $v['isValid'] = false;
//                            $v['msg'] = $arr['Message'];
//                            break;
//                        }
//                    }
                }

                break;

            case Call::ENTITY_TYPE:


                // First check if the workshop is expired.
                $call = $data['item'];

                /**
                 * @var \Application\Models\User
                 */
                $userM = Model::get(User::class);
                $userInfo = $userM->getInfo();
                if (
                    $call['status'] == Workshop::STATUS_COMPLETED ||
                    $call['status'] == Workshop::STATUS_CANCELED ||
                    $call['status'] == Workshop::STATUS_NOT_STARTED
                ) {
                    $v['isValid'] = false;
                    $v['msg'] = $lang('call_join_' . $call['status']);

                    // if not started and advisor yet to clicked on start button
                    if ($call['status'] == Call::STATUS_NOT_STARTED && time() > strtotime($call['date'])) {
                        $v['msg'] = $lang("service_join_advisor_not_started");
                    }

                    // if not started and user is not advisor show not started 
                    if ($call['status'] == Call::STATUS_NOT_STARTED && WorkshopHelper::isExpired($call['date'], $call['duration'])) {
                        $v['msg'] = $lang("call_join_expired");
                    }
                    break;
                } else {
                    $config = Config::get("Big");
                    $wConfig = Config::get("Website");

                    $valid = strtotime($call['date']) + (($call['duration'] - $wConfig->join_padding) * 60) > time();
                    if (!$valid) {
                        $v['isValid'] = false;
                        $v['msg'] = $lang("call_join_expired");
                        break;
                    }

                    $isAdvisor = $call['owner_id'] == $userInfo['id'];

//                    if (!$isAdvisor) {
//                        /**
//                         * @var \Application\Models\Meeting
//                         */
//                        $meetingM = Model::get(Meeting::class);
//                        $meetingInfo = $meetingM->getByEntity($call['id'], Call::ENTITY_TYPE);
//
//                        $data = array(
//                            'url' => $config->check_meeting_url,
//                            'fields' => [
//                                'serverURL' => $config->server_url,
//                                'name' => $userInfo['name'],
//                                'meetingID' => $meetingInfo['meeting_id'],
//                                'userID' => $userInfo['id']
//                            ]
//                        );
//
//                        $meetinApiM = Model::get(MeetingApi::class);
//                        $result = $meetinApiM->index($data);
//
//                        $arr = json_decode($result, true);
//
//                        if ($arr['Status'] == 0) {
//                            $v['isValid'] = false;
//                            $v['msg'] = $arr['Message'];
//                            break;
//                        }
//                    }

                }


                break;
        }


        return $v;
    }

    public function validateUserCancel($data)
    {
    }

    public function onJoin($data)
    {
        $type = $data['type'];
        $entityId = $data['item']['id'];
        $entityDto = null;
        $userM = Model::get(User::class);
        $userInfo = $userM->getInfo();
        $meetingM = Model::get(Meeting::class);

        switch ($type) {
            case Workshop::ENTITY_TYPE:
                $entityDto = new WorkshopDto(
                    $data['item']['id'],
                    $data['item']['user_id'],
                    $data['item']['name'],
                    $data['item']['desc'],
                    $data['item']['date'],
                    $data['item']['duration'],
                    $data['item']['price']
                );

                break;
            case Call::ENTITY_TYPE:
                $entityDto = new CallDto(
                    $data['item']['id'],
                    $data['item']['owner_id'],
                    null,
                    null,
                    $data['item']['date'],
                    $data['item']['duration'],
                    $data['item']['price']
                );

                break;
        }

        $meetingInfo = $meetingM->getByEntity($entityId, $type);
        $meetingDto = new MeetingDto($meetingInfo['id'], $meetingInfo['entity_id'], $meetingInfo['entity_type'], $meetingInfo['meeting_id'], $meetingInfo['meeting_url'], $meetingInfo['meeting_type']);
        $result = null;

        if (AppHelper::isMeetingProvider(AppHelper::DYTE_PROVIDER)) {
            $meetingProvider = new DyteProvider($entityDto);
            $result = $meetingProvider->setUpJoin($meetingDto);
        } elseif (AppHelper::isMeetingProvider(AppHelper::ZOOM_PROVIDER)) {
            $meetingProvider = new ZoomProvider($entityDto);
            $result = $meetingProvider->setUpJoin($meetingDto);
        } elseif (AppHelper::isMeetingProvider(AppHelper::BIG_BLUE_BUTTON_PROVIDER)) {
            if ($type == Call::ENTITY_TYPE) {
                $meetingProvider = new ZoomProvider($entityDto);
                $result = $meetingProvider->setUpJoin($meetingDto);
            } else {
                $meetingProvider = new BigBlueButtonProvider($entityDto);

                $meetingProvider->setUpJoin($meetingDto);
                $result = $meetingProvider->meetingInfo;
            }
        }


        $serviceM = Model::get(ServiceLog::class);
        $serviceM->create([
            'type' => ServiceLog::TYPE_USER,
            'action' => ServiceLog::ACTION_JOIN,
            'entity_id' => $type == Call::ENTITY_TYPE ? $data['item']['slot_id'] : $data['item']['id'],
            'entity_type' => $type,
            'action_by' => $userInfo['id'],
            'created_at' => time()
        ]);

        return $result;
    }

    public function onStart($data)
    {
        $type = $data['type'];
        $entityDto = null;

        switch ($type) {
            case Workshop::ENTITY_TYPE:
                $entityDto = new WorkshopDto(
                    $data['item']['id'],
                    $data['item']['user_id'],
                    $data['item']['name'],
                    $data['item']['desc'],
                    $data['item']['date'],
                    $data['item']['duration'],
                    $data['item']['price']
                );

                break;
            case Call::ENTITY_TYPE:
                $entityDto = new CallDto(
                    $data['item']['id'],
                    $data['item']['owner_id'],
                    null,
                    null,
                    $data['item']['date'],
                    $data['item']['duration'],
                    $data['item']['price']
                );
                break;
        }

        $entityType = ucfirst($type);
        $entityServiceClass = "\Application\Services\\{$entityType}Service";
        $entityService = new $entityServiceClass();
        $entityService->onStart($type, $entityDto);
    }
}
