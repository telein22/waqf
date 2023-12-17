<?php

namespace Application\Services;

class BaseService
{
    public static function init()
    {
        return new static();
    }
}