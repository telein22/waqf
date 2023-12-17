<?php

namespace Application\Values;

use Application\Models\Coupons;

class Coupon
{
    public string $code;
    public string $type;
    public float $amount;
    public ?string $userId;
    public ?string $entityType;
    public ?string $entityId;

    public function __construct(string $code, string $type, float $amount, ?string $userId = null, ?string $entityType = null, ?string $entityId = null)
    {
        $this->code = $code;
        $this->type = $type;
        $this->amount = $amount;
        $this->userId = $userId;
        $this->entityType = $entityType;
        $this->entityId = $entityId;
    }

    public function isFullDiscount(string $amount): bool
    {
        if (($this->type == Coupons::TYPE_PERCENT && $this->amount == 100)
            || ($this->type == Coupons::TYPE_FIXED && $amount == $this->amount))
            return true;
        return false;
    }
}