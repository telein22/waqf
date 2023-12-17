<?php

namespace Application\Dtos;

use Application\Models\User;
use System\Core\Model;

class Conversation extends BaseItem
{
    public function getName(): string
    {
        if ($userId = $this->getUserId()) {
            $userM = Model::get(User::class);
            $user = $userM->getUser($userId);

            return "رسالة تيلي إن إلى {$user['name']}";
        }
    }
}