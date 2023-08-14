<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Province;
use App\Repository\ProvinceRepositoryInterface;

class ProvinceRepository extends EloquentRepository implements ProvinceRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Province::class;
    }

}
