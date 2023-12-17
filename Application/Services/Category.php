<?php

namespace Application\Services;

use Application\Models\Specialty;
use Application\Models\UserSubSpecialty;
use System\Core\Model;

class Category extends BaseService
{
    public function get()
    {
        $subM = Model::get(UserSubSpecialty::class);
        $subSpecs = $subM->getTrending(10);

        $ids  = [];
        foreach ( $subSpecs as $specs ) {
            $ids[] = $specs['special_id'];
        }

        $specM = Model::get(Specialty::class);
        $specs = $specM->getByIdList($ids);

        foreach ( $subSpecs as & $s ) {
            $s['parent'] = isset($specs[$s['special_id']]) ? $specs[$s['special_id']] : null;
        }

        return $subSpecs;
    }
}