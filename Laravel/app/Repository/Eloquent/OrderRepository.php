<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Order;
use App\Repository\OrderRepositoryInterface;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Order::class;
    }

}
