<?php

namespace Application\Dtos;

interface Item
{
    public function getId(): int;

    public function getUserId(): int;

    public function getName(): string;

    public function getDescription(): ?string;

    public function getDate(): string;

    public function getPrice(): ?float;

    public function getDuration(): ?int;

    public function getCreatedBy(): ?int;
}