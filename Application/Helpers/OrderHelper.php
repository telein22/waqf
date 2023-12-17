<?php

namespace Application\Helpers;

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Order;
use Application\Models\Workshop;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\Strings;

class OrderHelper
{
    public static function prepare($data)
    {
        if (empty($data)) return $data;

        $lang = Model::get('\Application\Models\Language');

        // First try to grab all the user ids
        $userIds = array();
        $workshopIds = array();
        $callIds = [];
        $conversationIds = array();
        $charityIds = array();

        foreach ($data as $item) {
            if ($item['for_charity'] != '[]' && $item['for_charity'] != '0') {
                /**
                 * @var \Application\Models\Charity
                 */
                $charityM = Model::get('\Application\Models\Charity');
                $allCharities = $charityM->getByIds(json_decode($item['for_charity']), true);
                $charityIds = array_merge(json_decode($item['for_charity'], true), $charityIds);
            }


            $userIds[$item['user_id']] = $item['user_id'];
            $userIds[$item['entity_owner_id']] = $item['entity_owner_id'];

            switch ($item['entity_type']) {
                case Workshop::ENTITY_TYPE:
                    $workshopIds[$item['entity_id']] = $item['entity_id'];
                    break;
                case Call::ENTITY_TYPE:
                    $callIds[$item['entity_id']] = $item['entity_id'];
                    break;
                case Conversation::ENTITY_TYPE:
                    $conversationIds[$item['entity_id']] = $item['entity_id'];
                    break;
            }
        }

        // Fetch all users
        /**
         * @var \Application\Models\User
         */
        $userM = Model::get('\Application\Models\User');
        $users = $userM->getInfoByIds($userIds);

        /**
         * @var \Application\Models\Workshop
         */
        $workshopM = Model::get('\Application\Models\Workshop');
        $workshops = $workshopM->getInfoByIds($workshopIds);

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $calls = $callM->getInfoByIds($callIds);

        /**
         * @var \Application\Models\Conversation
         */
        $conM = Model::get('\Application\Models\Conversation');
        $conversations = $conM->getInfoByIds($conversationIds);

        /**
         * @var \Application\Models\Transfer
         */
        $transferM = Model::get('\Application\Models\Transfer');

        /**
         * @var \Application\Models\Payment
         */
        $paymentM = Model::get('\Application\Models\Payment');

        $cancelPadding = Config::get('Website')->user_cancel_padding;

        foreach ($data as &$item) {
            $charities = 'NA';

            if ($item['for_charity'] != '[]' && $item['for_charity'] != '0') {
                /**
                 * @var \Application\Models\Charity
                 */
                $charityM = Model::get('\Application\Models\Charity');
                $allCharities = $charityM->getByIds($charityIds, true);

                $charities = [];
                foreach (json_decode($item['for_charity'], true) as $charity) 
                {
                    $charities[] = '#' . $charity . ' - ' . $allCharities[$charity][$lang->current() . '_name'] ;
                }

                $charities = implode(', ', $charities);
            }

            $item['payment'] = array();

            $payments = $paymentM->all($item['id']);

            if (!empty($payments)) {
                $item['payment'] = $payments[0];
            }

            $receiverType = $charities == 'NA' ? 'advisor' : 'charity';

            $item['receiverType'] = $receiverType;

            $item['user'] = isset($users[$item['user_id']]) ? $users[$item['user_id']] : null;
            unset($item['user_id']);

            // add entity owner
            $item['entity_owner'] = isset($users[$item['entity_owner_id']]) ? $users[$item['entity_owner_id']] : null;
            unset($item['entity_owner_id']);

            $canUserCancel = !in_array($item['status'], [
                Order::STATUS_CANCELED,
                Order::STATUS_COMPLETED,
                Order::STATUS_INCOMPLETE
            ]);

            // Now its time to add entities
            switch ($item['entity_type']) {
                case Workshop::ENTITY_TYPE:
                    $item['entity'] = isset($workshops[$item['entity_id']]) ? $workshops[$item['entity_id']] : null;

                    $canUserCancel = $canUserCancel &&  $item['entity'] && strtotime($item['entity']['date']) > time() + $cancelPadding;

                    break;
                case Call::ENTITY_TYPE:
                    $item['entity'] = isset($calls[$item['entity_id']]) ? $calls[$item['entity_id']] : null;
                    $item['entity']['name'] = DateHelper::butify(strtotime($item['entity']['date']));

                    $canUserCancel = $canUserCancel &&  $item['entity'] && strtotime($item['entity']['date']) > time() + $cancelPadding;

                    break;
                case Conversation::ENTITY_TYPE:
                    $item['entity'] = isset($conversations[$item['entity_id']]) ? $conversations[$item['entity_id']] : null;
                    $item['entity']['name'] = htmlentities(Strings::limit($item['entity']['first_message'], 50));

                    $canUserCancel = $canUserCancel &&  $item['entity'] && $item['status'] !== Order::STATUS_APPROVED;
                    break;
            }

            $item['can_user_cancel'] = $canUserCancel;
            $item['charities'] = $charities;
        }

        return $data;
    }
}
