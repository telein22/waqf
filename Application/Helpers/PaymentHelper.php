<?php

namespace Application\Helpers;

use Application\Models\Payment;
use System\Core\Config;

class PaymentHelper
{
    public static function getGetWayPercentageCut(float $amount, string $paymentMethod): float
    {
        if ($amount == 0) {
            return 0;
        }

        $config = Config::get("Website");

        switch ($paymentMethod) {
            case Payment::METHOD_MADA:
                return ($config->mada_percent_cut / 100) * $amount; // 1% of amount
            case Payment::METHOD_VISA:
                return (($config->visa_percent_cut / 100) * $amount) + 1; // 1 SR + 2.5% of amount
            case Payment::METHOD_STC:
                return (($config->stc_percent_cut / 100) * $amount) + 1; // 1 SR + 1.70% of amount

            default: return 0;
        }
    }
}