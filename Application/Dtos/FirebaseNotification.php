<?php

namespace Application\Dtos;

class FirebaseNotification
{
    private ?string $Fcm;
    private string $title;
    private string $body;
    private ?FirebaseNotificationData $data;

    public function __construct(?string $fcm, string $title, string $body, ?FirebaseNotificationData $data = null)
    {
        $this->fcm = $fcm;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function getFcm(): ?string
    {
        return $this->fcm;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getData(): array
    {
        if (!$this->data) {
            return [];
        }

        return $this->data->getDataList();
    }

}