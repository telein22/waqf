<?php

namespace Application\Helpers;

use Application\Modules\Events\CalendarEvent;

class Calendar
{
private string $data;
private CalendarEvent $calendarEvent;

    public function __construct(CalendarEvent $calendarEvent)
    {
        $this->calendarEvent = $calendarEvent;
        $this->data = <<<EOT
BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
DTSTART:{$calendarEvent->getStartDate()}
DTEND:{$calendarEvent->getEndDate()}
LOCATION:{$calendarEvent->getLocation()}
TRANSP:OPAQUE
SEQUENCE:0
UID:{$calendarEvent->getUUID()}
DTSTAMP:{$calendarEvent->getCreationDate()}
ORGANIZER;CN={$calendarEvent->getOrganizer()}
SUMMARY:{$calendarEvent->getEventName()}
DESCRIPTION:{$calendarEvent->getDescription()} \n MeetingProviders Link: {$calendarEvent->getLink()}
PRIORITY:1
CLASS:PUBLIC
BEGIN:VALARM
TRIGGER:-PT10080M
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
END:VEVENT
END:VCALENDAR
EOT;
    }

    public function generate()
    {
        file_put_contents($this->calendarEvent->getFullPath(), $this->data);
        return $this;
    }

    public function getEvent(): CalendarEvent
    {
        return $this->calendarEvent;
    }
}