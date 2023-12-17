<?php

namespace Application\Hooks;

use Application\Models\Email;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\Strings;
use Application\Models\Notification;
use Application\Models\Participant;
use Application\Models\Workshop;
use System\Helpers\URL;

class Feed
{
    public function checkLatest($collection)
    {
        $values = $collection->getValues('feed.check');
        if (!$values) return;

        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $userInfo = $userM->getInfo();

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $data = $feedM->checkLatest($userInfo['id'], $values[0], true);

        $collection->set('feed.check', $data);
    }

    public function create($data)
    {
        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feedM->create($data);
    }

    public function onCreate($data)
    {
        $dd = json_decode($data['data']['data'], true);

        if (isset($dd['workshop'])) {
            /**
             * @var \Application\Models\Workshop
             */
            $workshopM = Model::get('\Application\Models\Workshop');
            $workshopInfo = $workshopM->getInfoById($dd['workshop']);

            if (!empty($workshopInfo['invite'])) {
                $username = str_replace('@', '', $workshopInfo['invite']);

                /**
                 * @var \Application\Models\User
                 */
                $userM = Model::get('\Application\Models\User');
                $receiverInfo = $userM->checkUserNameExists($username);
                $userInfo = $userM->getInfo();

                $arr = array(
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
                $notifiM->create($arr);

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
                    'entity_type' => Workshop::ENTITY_TYPE,
                    'user_id' => $receiverInfo['id'],
                    'participated_at' => time()
                ]);
            }
        }

        $text = $data['text'];

        $arr = Strings::explode(' ', $text);

        $blockedWM = Model::get('\Application\Models\BlockedWords');
        $words = $blockedWM->getBlockedWordByWords($arr);

        $blockedWFM = Model::get('\Application\Models\BlockedFeedWords');
        // $hiddenEM = Model::get('\Application\Models\HiddenEntities');

        foreach ($words as $word) {
            $blockedWFM->create(array(
                'entity_id' => $data['feed_id'],
                'entity_type' => 'feed',
                'word' => $word['word']
            ));
        }

        // if( !empty($words) )
        // {
        //     $hiddenEM->create(array(
        //         'entity_id' => $data['feed_id'],
        //         'entity_type' => 'feed'
        //     ));
        // }
    }
}
