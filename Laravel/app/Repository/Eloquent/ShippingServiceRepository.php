<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingService;
use App\Repository\ShippingServiceRepositoryInterface;

class ShippingServiceRepository extends EloquentRepository implements ShippingServiceRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingService::class;
    }

}
