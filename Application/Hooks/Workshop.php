<?php

namespace Application\Hooks;

use Application\Helpers\AppHelper;
use Application\Helpers\Calendar;
use Application\Models\Email;
use Application\Models\Language;
use Application\Modules\Events\WorkshopEvent;
use Application\ThirdParties\QRcode\QRcodeGenerator;
use System\Core\Model;
use Application\Models\Notification;
use Application\Models\Participant;
use Application\Models\Workshop as ModelsWorkshop;
use System\Helpers\URL;
use Application\Models\User;
use Application\ThirdParties\Whatsapp\Whatsapp;

class Workshop
{

    public function onCreate($data)
    {
        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feedId = $feedM->create($data);

        $userM = Model::get("\Application\Models\User");
        $userInfo = $userM->getInfo();

        $arr = json_decode($data['data'], true);


        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshopInfo = $workshopM->getInfoById($arr['workshop']);

        if (!empty($workshopInfo['invite'])) {
            $username = str_replace('@', '', $workshopInfo['invite']);

            /**
             * @var \Application\Models\User
             */
            $userM = Model::get('\Application\Models\User');
            $receiverInfo = $userM->checkUserNameExists($username);
            $userInfo = $userM->getInfo();

            $data = array(
                'sender_id' => $workshopInfo['user_id'],
                'receiver_id' => $receiverInfo['id'],
                'type' => Notification::TYPE_SERVICE,
                'action_type' => Notification::ACTION_WORKSHOP_INVITED,
                'data' => json_encode($workshopInfo),
                'read' => 0,
                'sent' => 0,
                'created_at' => time()
            );

            $notifiM = Model::get('\Application\Models\Notification');
            $data = $notifiM->create($data);

            /**
             * @var Language
             */
            $language = Model::get(Language::class);
            $entityOwnerLang = $language->getUserLang($receiverInfo['id']);

            /**
             * @var Email
             */
            $emailM = Model::get(Email::class);
            $mail = $emailM->new();

            $emailData = array(
                'user' => $userInfo,
                'entity_info' => $workshopInfo,
            );
           

            $mail->to([$receiverInfo['email'], $receiverInfo['name']]);
            $mail->body('Emails/' . 'workshop_invited', [
                'info' => $emailData,
                'name' => $receiverInfo['name'],
                'url' => URL::full('')
            ], $entityOwnerLang);
            $mail->subject('workshop_invited', null, $entityOwnerLang);

            
            
            $mail->send();

            /**
             * @var Participant
             */
            $partiM = Model::get(Participant::class);
            $partiM->create([
                'entity_id' => $workshopInfo['id'],
                'entity_type' => ModelsWorkshop::ENTITY_TYPE,
                'user_id' => $receiverInfo['id'],
                'participated_at' => time()
            ]);
        }


        $baseURL = AppHelper::getBaseUrl();
        $message = <<<MESSAGE
أهلاً بك {$userInfo['name']}

لقد تم انشاء جلستك بنجاح تحت عنوان "{$workshopInfo['name']}" وسوف يتم ارسال رابط الجلسة لك عبر الواتس اب والايميل قبل موعد الجلسة ب 15 دقيقه

لاضافة موعد وتفاصيل الجلسة الى التقويم الخاص بك الرجاء الضغط على الملف المرسل اليك

كما تستطيع نشر رابط الجلسة عن طريق الرابط التالي

{$baseURL}/feed/{$feedId}

كما نهديك اعلان لجلستك (بوستر) تستطيع نشره ومشاركته مع من تحب والذي يحتوي على تفاصيل الجلسة بالمرفق التالي

شكرا لك   
MESSAGE;

        Whatsapp::sendChat($userInfo['phone'], $message);

        // generate a calendar for the workshop
        $workshopEvent = new WorkshopEvent(
            $workshopInfo['id'],
            $workshopInfo['user_id'],
            $workshopInfo['date'],
            $workshopInfo['duration'],
            $workshopInfo['name'],
            $workshopInfo['desc']
        );

        $calendar = (new Calendar($workshopEvent))->generate();
        Whatsapp::sendDocument($userInfo['phone'], $workshopEvent);


        QRcodeGenerator::generateQrCode($feedId, QRcodeGenerator::CONTENT_TYPE_POSTER);
    }

    public function onDelete($workshop)
    {

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feed = $feedM->getFeedByRef(ModelsWorkshop::ENTITY_TYPE . '_' . $workshop['id']);

        if ($feed) {
            $data = json_decode($feed['data'], true);

            if (isset($data['workshop'])) {
                $data['workshop'] = 'deleted';
                $feedM->update($feed['id'], array(
                    'data' => json_encode($data)
                ));
            }

        }


    }

    public function upcomingOrCurrent($collection)
    {
        $userId = User::getId();
        $workshopM = Model::get(ModelsWorkshop::class);
        $workshop = $workshopM->upcomingOrCurrent($userId);
        $user = null;

        if (isset($workshop['user_id'])) {
            $userM = Model::get(User::class);
            $user = $userM->getUser($workshop['user_id']);
        }

        $collection->set('upcomingOrCurrentWorkshop', [
            'workshop' => $workshop,
            'user' => $user,
            'isAdvisor' => isset($workshop['user_id']) ? $workshop['user_id'] == $userId : false,
        ]);
    }
}
