<?php

namespace Application\ThirdParties\MeetingProviders;

use Application\Dtos\Item;

abstract class BaseMeetingProvider
{
    protected $item;
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}