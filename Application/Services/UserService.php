<?php

namespace Application\Services;

use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;

class UserService extends BaseService
{
    public function findWorkshopInGivenPeriod(int $userId, string $datetime, int $duration)
    {
        $workshopM = Model::get(Workshop::class);
        return $workshopM->findWorkshopByGivenPeriod($userId, $datetime, $duration);
    }

    public function getHighRatedUsers()
    {
        $userM = Model::get(User::class);
        return $userM->getHighRatedUsers();
    }
}