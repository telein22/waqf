<?php

namespace Application\Dtos;

use Application\Models\User;
use System\Core\Model;

class Call extends BaseItem
{

    public function getName(): string
    {
        if ($userId = $this->getUserId()) {
            $userM = Model::get(User::class);
            $user = $userM->getUser($userId);

            return "مكالمة تيلي إن مع {$user['name']}";
        }
    }

    public function getOwnerName(): string
    {
        if ($userId = $this->getUserId()) {
            $userM = Model::get(User::class);
            $user = $userM->getUser($userId);

            return $user['name'];
        }
    }

    public function getDescription(): ?string
    {
        return '';
    }
}