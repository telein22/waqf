<?php

namespace Application\Helpers;

use Application\Models\Conversation;
use Application\Models\Message;
use Application\Models\User;
use System\Core\Config;
use System\Core\Model;

class CommissionHelper
{
    public static function prepare($data)
    {
        if (empty($data)) return $data;

        $entityIds = array_column($data, 'entity_id');
        $advisorIds = array_column($data, 'advisor_id');

        $userIds = [...$entityIds, ...$advisorIds];

        $userM = Model::get(User::class);
        $users = $userM->getInfoByIds($userIds);

        foreach ($data as & $item) {
            $item['entity'] = isset($users[$item['entity_id']]) ? $users[$item['entity_id']] : null;
            unset($item['entity_id']);

            $item['advisor'] = isset($users[$item['advisor_id']]) ? $users[$item['advisor_id']] : null;
            unset($item['advisor_id']);
        }

        return $data;
    }
}