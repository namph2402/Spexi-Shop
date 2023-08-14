<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Cart;
use App\Repository\CartRepositoryInterface;

class CartRepository extends EloquentRepository implements CartRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Cart::class;
    }

}
