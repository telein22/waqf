<?php

namespace Application\Helpers;

use Application\Models\ProfitProceed;
use System\Core\Model;

class ProfitsProceedHelper
{
    public static function getProfitsProceeds(): array
    {
        $profitProceedM = Model::get(ProfitProceed::class);
        return $profitProceedM->getAll();
    }
}