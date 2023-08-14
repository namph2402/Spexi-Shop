<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\District;
use App\Repository\DistrictRepositoryInterface;

class DistrictRepository extends EloquentRepository implements DistrictRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return District::class;
    }

}
