<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ShippingStore;
use App\Repository\ShippingStoreRepositoryInterface;

class ShippingStoreRepository extends EloquentRepository implements ShippingStoreRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ShippingStore::class;
    }

}
