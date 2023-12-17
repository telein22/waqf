<?php

namespace Application\Modules\Events;

use Application\Helpers\AppHelper;

class WorkshopEvent extends BaseEvent implements CalendarEvent
{
    public function getFileName(): string
    {
        return sprintf("%s-%d", "workshop", $this->id);
    }

    public function getLink(): string
    {
        return AppHelper::getBaseUrl() . '/waiting-room/sessions/' . $this->id;
    }
}