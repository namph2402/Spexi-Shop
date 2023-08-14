<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Ward;
use App\Repository\WardRepositoryInterface;

class WardRepository extends EloquentRepository implements WardRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Ward::class;
    }

}
