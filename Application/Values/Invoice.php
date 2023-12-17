<?php

namespace Application\Values;

use Application\Dtos\Item;
use Application\Dtos\Order as OrderDto;

class Invoice
{
    public Item $item;
    public OrderDto $order;

    public function __construct(Item $item, OrderDto $order)
    {
        $this->item = $item;
        $this->order = $order;
    }
}