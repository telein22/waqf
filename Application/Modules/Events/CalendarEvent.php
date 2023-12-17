<?php

namespace Application\Modules\Events;

use Application\Modules\SendibleAsAttachment;

interface CalendarEvent extends SendibleAsAttachment
{
    public function getCreationDate(): string;

    public function getStartDate(): string;

    public function getEndDate(): string;

    public function getEventName(): string;

    public function getDescription(): ?string;

    public function getLink(): string;

    public function getUUID(): string;

    public function getOrganizer(): string;

    public function getLocation(): string;
}