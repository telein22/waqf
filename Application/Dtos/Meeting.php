<?php

namespace Application\Dtos;

class Meeting
{
    public int $id;
    public string $entityId;
    public string $entityType;
    public string $meetingId;
    public string $meetingUrl;
    public string $meetingType;

    public function __construct(int $id, string $entityId, string $entityType, string $meetingId, string $meetingUrl, string $meetingType)
    {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->entityType = $entityType;
        $this->meetingId = $meetingId;
        $this->meetingUrl = $meetingUrl;
        $this->meetingType = $meetingType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getMeetingType(): string
    {
        return $this->meetingType;
    }

    public function getMeetingUrl(): string
    {
        return $this->meetingUrl;
    }

}