<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingUnit;
use App\Repository\ShippingUnitRepositoryInterface;

class ShippingUnitRepository extends EloquentRepository implements ShippingUnitRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingUnit::class;
    }

}
