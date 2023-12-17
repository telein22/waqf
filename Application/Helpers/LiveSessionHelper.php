<?php

namespace Application\Helpers;

use Application\Models\CallSlot;
use Application\Models\Workshop;
use System\Core\Model;

class LiveSessionHelper
{
    public static function getCountAt( $startTime, $endTime )
    {
        /**
         * @var CallSlot
         */
        $callSM = Model::get(CallSlot::class);
        $totalSlots = $callSM->getSlotCountAt($startTime, $endTime);

        /**
         * @var Workshop
         */
        $workM = Model::get(Workshop::class);
        $totalSlots += $workM->getSlotCountAt($startTime, $endTime);

        return $totalSlots;
    }
}