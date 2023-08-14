<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\CartItem;
use App\Repository\CartItemRepositoryInterface;

class CartItemRepository extends EloquentRepository implements CartItemRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CartItem::class;
    }

}
