<?php

namespace Application\Modules\Events;

use Application\Helpers\AppHelper;
use Application\Models\User;
use System\Core\Model;

class CallEvent extends BaseEvent implements CalendarEvent
{
    public function getEventName(): string
    {
            $userM = Model::get(User::class);
            $user = $userM->getUser($this->user_id);

            return "مكالمة تيلي إن مع {$user['name']}";
    }

    public function getFileName(): string
    {
        return sprintf("%s-%d", "call", $this->id);
    }

    public function getDescription(): ?string
    {
        return 'Tele-Call';
    }

    public function getLink(): string
    {
        return AppHelper::getBaseUrl() . '/waiting-room/calls/' . $this->id;
    }
}