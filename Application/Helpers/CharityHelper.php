<?php

namespace Application\Helpers;

use Application\Models\Charity;
use System\Core\Model;

class CharityHelper
{
    public static function getCharities(): array
    {
        $charityM = Model::get(Charity::class);
        return $charityM->all();
    }
}