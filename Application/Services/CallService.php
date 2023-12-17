<?php

namespace Application\Services;

use Application\Services\Traits\EntityTrait;
use System\Core\Model;
use Application\Models\User;

class CallService
{
    use EntityTrait;

    public function create($date, $price)
    {
        if (is_null($price) || $price == false) {
            return;
        }

        $startDate = date('Y-m-d', strtotime($date));
        $startTime = date('H:i:s', strtotime($date));

        $callSM = Model::get('\Application\Models\CallSlot');
        $callSM->create(['user_id' => User::getId(),
            'date' => $startDate,
            'time' => $startTime,
            'price' => $price,
            'charity' => [],
            'created_at' => time()]);
    }
}