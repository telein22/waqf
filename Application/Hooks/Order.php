<?php

namespace Application\Hooks;

use Application\Controllers\Ajax\Messaging;
use Application\Dtos\FirebaseNotification;
use Application\Dtos\FirebaseNotificationData;
use Application\Helpers\AppHelper;
use Application\Helpers\Calendar;
use Application\Helpers\FirebaseHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Coupons;
use Application\Models\Workshop;
use Application\Modules\Events\CallEvent;
use Application\Modules\Events\WorkshopEvent;
use Application\Modules\Invoices\ParticipantInvoice;
use Application\ThirdParties\Firebase\Firebase;
use Application\ThirdParties\Whatsapp\Whatsapp;
use Application\ThirdParties\Whatsapp\WhatsappMessages;
use Application\Values\Invoice;
use Application\Dtos\Workshop as WorkshopDto;
use Application\Dtos\Call as CallDto;
use Application\Dtos\Conversation as ConversationDto;
use Application\Dtos\Order as OrderDto;
use Application\Values\Coupon as CouponValus;
use System\Core\Config;
use System\Core\Model;
use Application\Models\Notification;
use Application\Models\Email as ModelEmail;
use Application\Models\Language;
use Application\Models\Order as ModelsOrder;
use Application\Models\Settings as ModelsSettings;
use Application\Models\Payment;
use Application\Models\User;
use Application\Models\UserSettings;
use System\Helpers\URL;

class Order
{
    public function pendingCount($collection)
    {
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getInfo();

        $orderM = Model::get('\Application\Models\Order');

        $workshopCount = $orderM->totalCount($userInfo['id'], Workshop::ENTITY_TYPE);
        $callCount = $orderM->totalCount($userInfo['id'], Call::ENTITY_TYPE);
        $convoCount = $orderM->totalCount($userInfo['id'], Conversation::ENTITY_TYPE);

        $data = (int) $workshopCount + (int) $callCount + (int) $convoCount;

        $collection->set('pendingRequest.count', $data);
    }

    public function acceptValidate($order)
    {
        $data = [
            'isValid' => false,
            'msg' => null
        ];

        $lang = Model::get('\Application\Models\Language');

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Workshop
                 */
                $workM = Model::get('\Application\Models\Workshop');
                $workshop = $workM->getInfoByIds($order['entity_id']);
                if (empty($workshop)) {
                    $data['msg'] = $lang('workshop_not_exists');
                    break;
                }
                $workshop = $workshop[$order['entity_id']];

                // if work shop exists
                // we need to check for slots
                /**
                 * @var \Application\Models\Participant
                 */
                $partiM = Model::get('\Application\Models\Participant');
                $count = $partiM->count($order['entity_id'], Workshop::ENTITY_TYPE);
                $count = isset($count[$order['entity_id']]) ? $count[$order['entity_id']] : 0;

                if ($workshop['capacity'] <= $count) {
                    $data['msg'] = $lang('workshop_not_exists');
                    break;
                }

                $time = strtotime($workshop['date']);
                $timePlusDuration = strtotime("+{$workshop['duration']} minutes", $time);


                if ($timePlusDuration < time()) {
                    $data['msg'] = $lang('workshop_request_expired');
                    break;
                }

                $data['isValid'] = true;

                // accept
                break;
            case Call::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $call = $callM->getById($order['entity_id']);
                if (empty($call)) {
                    $data['msg'] = $lang('call_not_exists');
                    break;
                }

                // Check time expired
                $time = strtotime($call['date']);
                $timePlusDuration = strtotime("+{$call['duration']} minutes", $time);

                if ($timePlusDuration < time()) {
                    $data['msg'] = $lang('call_request_expired');
                    break;
                }

                $data['isValid'] = true;
                break;
            case Conversation::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Conversation
                 */
                $conM = Model::get('\Application\Models\Conversation');
                $conversation = $conM->getById($order['entity_id']);

                // Check if the time not expired.
                $timeout = Config::get("Website")->conversation_timeout;
                $time = $conversation['created_at'] + $timeout;

                if ($time < time()) {
                    $data['msg'] = $lang('conversation_request_expired');
                    break;
                }

                $data['isValid'] = true;

                break;
        }

        return $data;
    }

    public function cancelValidate($order)
    {
        return [
            'isValid' => true,
            'msg' => null
        ];
    }

    public function userCancelValidate( $order )
    {
        $data = [ 'isValid' => true, 'msg' => null ];

        /**
         * @var Language
         */
        $lang = Model::get(Language::class);

        $config = Config::get('Website');
        $padding = $config->user_cancel_padding;

        switch( $order['entity_type'] )
        {
            case Workshop::ENTITY_TYPE:

                /**
                 * @var Workshop
                 */
                $workM = Model::get(Workshop::class);
                $workshop = $workM->getInfoById($order['entity_id']);

                if ( empty($workshop) )
                {
                    $data['isValid'] = false;
                    $data['msg'] = "Invalid request";
                    break;
                }

                if ( strtotime($workshop['date']) < time() + $padding )
                {
                    $data['isValid'] = false;
                    $data['msg'] = $lang('user_cant_cancel_anymore');
                }

                break;
            case Call::ENTITY_TYPE:

                /**
                 * @var Call
                 */
                $callM = Model::get(Call::class);
                $call = $callM->getById($order['entity_id']);

                if ( empty($call) )
                {
                    $data['isValid'] = false;
                    $data['msg'] = "Invalid request";
                    break;
                }

                if ( strtotime($call['date']) < time() + $padding )
                {
                    $data['isValid'] = false;
                    $data['msg'] = $lang('user_cant_cancel_anymore');
                }

                break;
            case Conversation::ENTITY_TYPE:
                /**
                 * @var Conversation
                 */
                $conM = Model::get(Conversation::class);
                $conversation = $conM->getById($order['entity_id']);

                if ( empty($conversation) )
                {
                    $data['isValid'] = false;
                    $data['msg'] = "Invalid request";
                    break;
                }

                if ( $order['status'] === ModelsOrder::STATUS_PENDING )
                {
                    $data['isValid'] = false;
                    $data['msg'] = $lang('user_cant_cancel_anymore');
                }

                break;
            default:
                // awesome
        }

        return $data;
    }

    public function accept( $order )
    {

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:
                $participants = [$order['user_id']];

                // $workshopM = Model::get('\Application\Models\Workshop');
                // $workshopInfo = $workshopM->getInfoById( $order['entity_id'] );

                // $data = array(
                //     'sender_id' => $workshopInfo['user_id'],
                //     'receiver_id' => $order['user_id'],
                //     'type' => Notification::TYPE_SERVICE,
                //     'action_type' => Notification::ACTION_WORKSHOP_ACCEPTED,
                //     'data' => json_encode($workshopInfo),
                //     'read' => 0,
                //     'sent' => 0,
                //     'created_at' => time()
                // );
    
                // $notifiM = Model::get('\Application\Models\Notification');
                // $data = $notifiM->create($data);

                break;
            case Call::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                // $callInfo = $callM->getById( $order['entity_id'] );

                // $data = array(
                //     'sender_id' => $callInfo['owner_id'],
                //     'receiver_id' => $callInfo['created_by'],
                //     'type' => Notification::TYPE_SERVICE,
                //     'action_type' => Notification::ACTION_CALL_ACCEPTED,
                //     'data' => json_encode($callInfo),
                //     'read' => 0,
                //     'sent' => 0,
                //     'created_at' => time()
                // );
    
                // $notifiM = Model::get('\Application\Models\Notification');
                // $data = $notifiM->create($data);
                
                $callM->update($order['entity_id'], [
                    'is_temp' => 0
                ]);
                $participants = [$order['user_id']];
                break;
            case Conversation::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Conversation
                 */
                $conM = Model::get('\Application\Models\Conversation');
                $con = $conM->getById($order['entity_id']);
                $participants = [$con['owner_id'], $order['user_id']];

                // Create the first message
                /**
                 * @var \Application\Models\Message
                 */
                $msgM = Model::get('\Application\Models\Message');
                $lastMessageId = $msgM->create([
                    'conversation_id' => $order['entity_id'],
                    'message' => $con['first_message'],
                    'sender_id' => $order['user_id'],
                    'receiver_id' => $con['owner_id'],
                    'created_at' => time()
                ]);
                
                // Update conversation that its not temp any more
                // also update last message id.
                $conM->update($order['entity_id'], [
                    'is_temp' => 0,
                    'last_message_id' => $lastMessageId
                ]);

                // /**
                //  * @var \Application\Models\Conversation
                //  */
                // $conversationM = Model::get('\Application\Models\Conversation');
                // $convoInfo = $conversationM->getById( $order['entity_id'] );

                // $data = array(
                //     'sender_id' => $convoInfo['owner_id'],
                //     'receiver_id' => $convoInfo['created_by'],
                //     'type' => Notification::TYPE_SERVICE,
                //     'action_type' => Notification::ACTION_MESSAGE_ACCEPTED,
                //     'data' => json_encode($convoInfo),
                //     'read' => 0,
                //     'sent' => 0,
                //     'created_at' => time()
                // );
    
                // $notifiM = Model::get('\Application\Models\Notification');
                // $data = $notifiM->create($data);

                break;
        }
        // $userM = Model::get('\Application\Models\User');
        // $receiverInfo = $userM->getUser($order['user_id']);
        /**
         * @var Language
         */
        // $language = Model::get(Language::class);
        // $lang = $language->getUserLang($receiverInfo['id']);

        /**
         * @var ModelEmail
         */
        // $emailM = Model::get(ModelEmail::class);
        // $mail = $emailM->new();

        // $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
        // $mail->body('Emails/' . 'order_accepted', [
        //     'info' => $order,
        //     'name' => $receiverInfo['name'],
        //     'url' => URL::full('')
        // ], $lang);
        // $mail->subject('order_accepted', null, $lang);
        // $mail->send();

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');

        foreach ($participants as $participant) {
            $is = $partiM->isParticipated($participant, $order['entity_id'], $order['entity_type']);

            if ($is) continue;

            $partiM->create([
                'entity_id' => $order['entity_id'],
                'entity_type' => $order['entity_type'],
                'user_id' => $participant,
                'participated_at' => time()
            ]);
        }
    }

    public function success( $order )
    {
        /**
         * @var Language
         */
        $language = Model::get(Language::class);
        $baseURL = AppHelper::getBaseUrl();

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getInfo();
        $receiverInfo = $userM->getUser($order['entity_owner_id']);
        $entityOwnerLang = $language->getUserLang($receiverInfo['id']);
        $calendar = null;
        $invoice = null;
        $entityDto = null;
        $actionType = null;
        $firebaseMessage = null;

        $couponValue = null;
        if (!is_null($order['used_coupon'])) {
            $couponM = Model::get(Coupons::class);
            $coupon = $couponM->getCouponByCode($order['used_coupon']);
            $couponValue = new CouponValus($coupon['id'], $coupon['type'], $coupon['amount']);
        }

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:

                $workshopM = Model::get('\Application\Models\Workshop');
                $workshopInfo = $workshopM->getInfoById($order['entity_id']);
                $entityDto = new WorkshopDto(
                    $workshopInfo['id'],
                    $workshopInfo['user_id'],
                    $workshopInfo['name'],
                    $workshopInfo['desc'],
                    $workshopInfo['date'],
                    $workshopInfo['duration'],
                    $workshopInfo['price']
                );

                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get('\Application\Models\Notification');
                $actionType = Notification::ACTION_WORKSHOP_PENDING;
                $notiM->create([
                    'sender_id' => $userInfo['id'],
                    'receiver_id' => $order['entity_owner_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($workshopInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time(),
                ]);

                $message = WhatsappMessages::confirmBookingWorkshop($userInfo['name'], $workshopInfo['name']);
                $firebaseMessage = "قد تم تسجيلك لحضور الجلسة بنجاح تحت عنوان {$workshopInfo['name']}";

                Whatsapp::sendChat($userInfo['phone'], $message);

                // generate a calendar for the workshop
                $workshopEvent = new WorkshopEvent(
                    $entityDto->getId(),
                    $entityDto->getUserId(),
                    $entityDto->getDate(),
                    $entityDto->getDuration(),
                    $entityDto->getName(),
                    $entityDto->getDescription()
                );

                $calendar = (new Calendar($workshopEvent))->generate();
                Whatsapp::sendDocument($userInfo['phone'], $workshopEvent);

                break;
            case Call::ENTITY_TYPE:

                $callM = Model::get('\Application\Models\Call');
                $callInfo = $callM->getById($order['entity_id']);
                $entityDto = new CallDto(
                    $callInfo['id'],
                    $callInfo['owner_id'],
                    null,
                    null,
                    $callInfo['date'],
                    $callInfo['duration'],
                    $callInfo['price'],
                    $callInfo['created_by']
                );

                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get('\Application\Models\Notification');
                $actionType = Notification::ACTION_CALL_PENDING;
                $notiM->create([
                    'sender_id' => $userInfo['id'],
                    'receiver_id' => $order['entity_owner_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($callInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time(),
                ]);

                // For participant
                $message = WhatsappMessages::confirmCallBookingForTheParticipant($userInfo['name'], $entityDto->getOwnerName());
                $firebaseMessage = "قد تم تسجيلك لحضور مكالمة بنجاح تحت عنوان {$entityDto->getName()}";

                Whatsapp::sendChat($userInfo['phone'], $message);

                // For Advisor
                $owner = $userM->getUser($entityDto->getUserId());
                $advisorMessage = WhatsappMessages::confirmCallBookingForTheOwner($owner['name'], $userInfo['name']);
                Whatsapp::sendChat($owner['phone'], $advisorMessage);

                // generate a calendar for the call for participant
                $event = new CallEvent($entityDto->getId(), $entityDto->getUserId(), $entityDto->getDate(), $entityDto->getDuration());
                $calendar = (new Calendar($event))->generate();
                Whatsapp::sendDocument($userInfo['phone'], $event);

                // generate a calendar for the call for owner
                $event = new CallEvent($entityDto->getId(), $userInfo['id'], $entityDto->getDate(), $entityDto->getDuration());
                $calendar = (new Calendar($event))->generate();
                Whatsapp::sendDocument($owner['phone'], $event);

                break;

            case Conversation::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Conversation
                 */
                $conversationM = Model::get('\Application\Models\Conversation');
                $convoInfo = $conversationM->getById($order['entity_id']);
                $entityDto = new ConversationDto(
                    $convoInfo['id'],
                    $convoInfo['owner_id'],
                    null,
                    null,
                    $convoInfo['date'],
                    null,
                    null,
                    $convoInfo['created_by']
                );

                /**
                 * @var \Application\Models\Notification
                 */
                $notiM = Model::get('\Application\Models\Notification');
                $actionType = Notification::ACTION_MESSAGE_PENDING;
                $notiM->create([
                    'sender_id' => $userInfo['id'],
                    'receiver_id' => $order['entity_owner_id'],
                    'type' => Notification::TYPE_SERVICE,
                    'action_type' => $actionType,
                    'data' => json_encode($convoInfo),
                    'read' => 0,
                    'sent' => 0,
                    'created_at' => time(),
                ]);

                $owner = $userM->getUser($entityDto->getUserId());
                $message = WhatsappMessages::confirmAfterSendingMessageForSender($userInfo['name'], $owner['name']);
                $firebaseMessage = $message;

                Whatsapp::sendChat($userInfo['phone'], $message);

                $advisorMessage = WhatsappMessages::confirmAfterSendingMessageForReceiver($userInfo['name'], $owner['name'], $entityDto->getId());
                Whatsapp::sendChat($owner['phone'], $advisorMessage);

                break;
        }

        // Update coupon count if there is any.
        if ($order['used_coupon'] !== null) {
            /**
             * @var Coupons
             */
            $couponM = Model::get(Coupons::class);
            $coupon = $couponM->getCouponByCode($order['used_coupon']);

            if (!empty($coupon)) {
                $couponM->update([
                    'used' => $coupon['used'] + 1
                ], $coupon['id']);
            }
        }
        $paymentM = Model::get('\Application\Models\Payment');
        $order['payment'] = $paymentM->getSuccessPayment($order['id']);


        $userM = Model::get('\Application\Models\User');
        $receiverInfo = $userM->getUser($order['user_id']);
        $entityOwnerInfo = $userM->getUser($order['entity_owner_id']);

        $receiverLang = $language->getUserLang($receiverInfo['id']);
        $entityOwnerLang = $language->getUserLang($entityOwnerInfo['id']);

        $order['user'] = $receiverInfo;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $order['user']['phone'] = $userSM->take($order['user_id'], UserSettings::KEY_PHONE);
        $country = $userSM->take($order['user_id'], UserSettings::KEY_COUNTRY);
        $city = $userSM->take($order['user_id'], UserSettings::KEY_CITY);

        if ($country) {
            /**
             * @var \Application\Models\Country
             */
            $countryM = Model::get('\Application\Models\Country');
            $order['user']['country'] = $countryM->getById($country);
        }

        if ($city) {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $order['user']['city'] = $cityM->getById($city);
        }


        $settingM = Model::get('\Application\Models\Settings');
        $platformFees = $settingM->take(ModelsSettings::KEY_PLATFORM_FEES, 0);
        $platformFees = $order['payable'] * $platformFees / 100;
        $platformFees = number_format(round($platformFees, 2), 2);
        $order['platform_fees'] = $platformFees;

        /// Invoice business logic
        $invoice = ParticipantInvoice::generate(
            new Invoice(
                $entityDto,
                new OrderDto(
                    $order['id'],
                    $userInfo['id'],
                    $order['amount'],
                    $order['payable'],
                    $order['final_amount'],
                    $order['advisor_amount'],
                    $couponValue
                )
            ));

        $orderM = Model::get(ModelsOrder::class);
        $orderM->updateInvoiceFileName($order['id'], $invoice->getFileName());

        $message = WhatsappMessages::confirmPayment($userInfo['name']);
        Whatsapp::sendChat($userInfo['phone'], $message);
        Whatsapp::sendDocument($userInfo['phone'], $invoice);
        /// Invoice business logic

        /// Firebase notifying
        /** Notify the owner */
        FirebaseHelper::notify($userInfo['id'], $order['entity_owner_id'], $actionType,
            new FirebaseNotificationData($order['entity_type'], $entityDto->getId()));

        /**  Notify the client */
        Firebase::notify(new FirebaseNotification($userInfo['fcm_token'], $language('incoming_notification'), $firebaseMessage,
            new FirebaseNotificationData($order['entity_type'], $entityDto->getId())));
        /// Firebase notifying


        /**
         * @var ModelEmail
         */
        $emailM = Model::get(ModelEmail::class);
        $mail = $emailM->new();

        $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
        $mail->body('Emails/' . 'order_created', [
            'info' => $order,
            'name' => $receiverInfo['name'],
            'url' => URL::full('')
        ], $receiverLang);
        $mail->subject('order_created', null, $receiverLang);

        if ($calendar) {
            $mail->addAttachment($calendar->getEvent()->getFullPath());
        }

        if ($invoice) {
            $mail->addAttachment($invoice->getFullPath());
        }

        $mail->send();

        // Advisor email
        $mail = $emailM->new();

        $mail->to([$entityOwnerInfo['email'], $entityOwnerInfo['name']]);
        $mail->body('Emails/' . 'order_created', [
            'info' => $order,
            'name' => $entityOwnerInfo['name'],
            'url' => URL::full('')
        ], $entityOwnerLang);
        $mail->subject('order_created', null, $entityOwnerLang);
        $mail->send();
    }

    public function cancel( $order )
    {

         /**
         * @var \Application\Models\Payment
         */
        $paymentsM = Model::get("\Application\Models\Payment");
        $payments = $paymentsM->all( $order['id'], Payment::STATUS_SUCCESS );

        foreach( $payments as $payment )
        {
            $paymentsM->update( $payment['id'], array('status' => Payment::STATUS_REFUND_INITIATED) );
        }

        $isAdvisor = $order['user_id'] !== User::getId();

        switch ($order['entity_type']) {
            case Workshop::ENTITY_TYPE:

                $participants = [$order['user_id']];
                break;
            case Call::ENTITY_TYPE:

                /**
                 * @var \Application\Models\Call
                 */
                $callM = Model::get('\Application\Models\Call');
                $callM->update($order['entity_id'], [
                    'status' => Call::STATUS_CANCELED
                ]);
                $participants = [$order['user_id']];
                break;
            case Conversation::ENTITY_TYPE:
                /**
                 * @var \Application\Models\Conversation
                 */
                $conM = Model::get('\Application\Models\Conversation');
                $conM->update($order['entity_id'], [
                    'status' => Conversation::STATUS_CANCELED
                ]);

                // Also remove conversation owner as participant
                $con = $conM->getById($order['entity_id']);
                $participants = [$con['owner_id'], $order['user_id']];


                break;
        }

        /**
         * @var \Application\Models\Participant
         */
        $partiM = Model::get('\Application\Models\Participant');

        foreach ($participants as $participant) {
            $is = $partiM->isParticipated($participant, $order['entity_id'], $order['entity_type']);

            if (!$is) return;

            $partiM->delete($order['entity_type'], $order['entity_id'], $participant);
        }
    }

}
