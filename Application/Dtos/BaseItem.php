<?php

namespace Application\Dtos;

abstract class BaseItem implements Item
{
    public int $id;
    public int $userId;
    public ?string $name;
    public ?string $description;
    public string $date;
    public ?int $duration;
    public ?float $price;
    public ?int $createdBy;

    public function __construct(int $id, int $userId, ?string $name, ?string $description, string $date, ?int $duration, ?float $price, ?int $createdBy = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->duration = $duration;
        $this->price = $price;
        $this->createdBy = $createdBy;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }
}