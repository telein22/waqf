<?php

namespace Application\Services;

use Application\Models\HashTags;
use System\Core\Model;

class HashTag extends BaseService
{
    public function get()
    {
        $hashTags = Model::get(HashTags::class);
        $hashTags = $hashTags->fetchMostUsed(10);

        return $hashTags;
    }
}