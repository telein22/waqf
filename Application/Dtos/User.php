<?php

namespace Application\Dtos;

use Application\Values\Coupon as CouponValue;

class User
{
public int $id;
public string $userName;
public string $amount;
public string $payable;
public string $finalAmount;
public ?CouponValue $coupon;

    public function __construct(int $id, string $username, string $amount, string $payable, string $finalAmount, ?CouponValue $coupon)
    {
        $this->id = $id;
        $this->userName = $username;
        $this->amount = $amount;
        $this->payable = $payable;
        $this->finalAmount = $finalAmount;
        $this->coupon = $coupon;
    }

}