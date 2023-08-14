<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Warehouse;
use App\Repository\WarehouseRepositoryInterface;

class WarehouseRepository extends EloquentRepository implements WarehouseRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Warehouse::class;
    }

}
