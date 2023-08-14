<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingFee;
use App\Repository\ShippingFeeRepositoryInterface;

class ShippingFeeRepository extends EloquentRepository implements ShippingFeeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingFee::class;
    }

}
