<?php

namespace Application\Modules\Events;

use Application\Models\User;
use Application\Modules\SendibleAsAttachment;
use System\Core\Model;

abstract class BaseEvent implements CalendarEvent
{
    public int $id;
    public int $user_id;
    public string $date;
    public ?string $name;
    public ?string $description;
    public int $duration;

    public function __construct(int $id, int $user_id, string $date, int $duration, ?string $name = null, ?string $description = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
        $this->duration = $duration;
        $this->name = $name;
        $this->description = $description;
    }

    abstract public function getLink(): string;

    public function getFileCaption(): string
    {
        return 'Click to add to your calendar!';
    }

    public function getFullPath(): string
    {
        return sprintf('Storage/Calendar/%s.ics', $this->getFileName());
    }

    public function getCreationDate(): string
    {
        return date("Ymd\THis");
    }

    public function getStartDate(): string
    {
        return date("Ymd\THis", strtotime($this->date));
    }

    public function getEventName(): string
    {
        return $this->name;
    }

    public function getEndDate(): string
    {
        $endDate = date('Y-m-d H:i:s', strtotime($this->date . " + {$this->duration} minute"));
        return date("Ymd\THis", strtotime($endDate));
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUUID(): string
    {
        return uniqid(); // Generate a unique ID for the event
    }

    public function getOrganizer(): string
    {
        return sprintf("%s:mailto:%s", 'منصة Telein', 'Telein.ceo@gmail.com');
    }

    public function getLocation(): string
    {
        return 'Online';
    }
}