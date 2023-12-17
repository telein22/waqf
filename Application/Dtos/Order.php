<?php

namespace Application\Dtos;

use Application\Values\Coupon as CouponValue;
use Application\Models\User as UserModel;
use System\Core\Model;

class Order
{
    private int $id;
    private int $userId;
    private string $amount;
    private string $payable;
    private string $finalAmount;

    private string $advisorAmount;
    private ?CouponValue $coupon;
    private ?int $entityOwnerId;

    public function __construct(int $id, int $userId, string $amount, string $payable, string $finalAmount, string $advisorAmount, ?CouponValue $coupon = null, ?int $entityOwnerId = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->payable = $payable;
        $this->finalAmount = $finalAmount;
        $this->advisorAmount = $advisorAmount;
        $this->coupon = $coupon;
        $this->entityOwnerId = $entityOwnerId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        $userM = Model::get(UserModel::class);
        $user = $userM->getUser($this->userId);

        return $user['name'];
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getPayable(): string
    {
        return $this->payable;
    }

    public function getFinalAmount(): string
    {
        return $this->finalAmount;
    }

    public function getAdvisorAmount(): string
    {
        return $this->advisorAmount;
    }

    public function getCoupon(): ?CouponValue
    {
        return $this->coupon;
    }

    public function getEntityOwnerId(): ?int
    {
        return $this->entityOwnerId;
    }
}