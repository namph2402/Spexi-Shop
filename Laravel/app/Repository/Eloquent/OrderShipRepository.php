<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\OrderShip;
use App\Repository\OrderShipRepositoryInterface;

class OrderShipRepository extends EloquentRepository implements OrderShipRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return OrderShip::class;
    }

}
