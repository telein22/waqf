<?php

namespace Application\Dtos;

class FirebaseNotificationData
{
    const PAGE_WORKSHOP_WAITING_ROOM = 'workshop_waiting_room';
    const PAGE_CALL_WAITING_ROOM = 'call_waiting_room';

    private string $page;
    private string $itemId;

    public function __construct(string $page, string $itemId)
    {
        $this->page = $page;
        $this->itemId = $itemId;
    }

    public function getDataList(): array
    {
        return [
            'page' => $this->page,
            'itemId' => $this->itemId
        ];
    }
}