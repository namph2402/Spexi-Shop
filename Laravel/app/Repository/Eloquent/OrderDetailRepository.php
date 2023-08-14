<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\OrderDetail;
use App\Repository\OrderDetailRepositoryInterface;

class OrderDetailRepository extends EloquentRepository implements OrderDetailRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return OrderDetail::class;
    }

}
