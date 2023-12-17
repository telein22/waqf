<?php

namespace Application\Services;

use Application\Models\FeedViewers;
use Application\Models\User;
use Application\Models\Workshop;
use Application\Models\Call;
use System\Core\Model;

class StatisticsService extends BaseService
{

    public function getUsersCount()
    {
        $userM = Model::get(User::class);
        return $userM->getUsersCount();
    }

    public function getNumberOfMinutesForPerformedWorkshops()
    {
        $workshopM = Model::get(Workshop::class);
        return $workshopM->getPerformedMinutes();
    }

    public function getNumberOfMinutesForPerformedCalls()
    {
        $callM = Model::get(Call::class);
        return $callM->getPerformedMinutes();
    }

    public function getNumberOfFeedViewers()
    {
        $feedM = Model::get(FeedViewers::class);
        return $feedM->getTotalViews();
    }
}