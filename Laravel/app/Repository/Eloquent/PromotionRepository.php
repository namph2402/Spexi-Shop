<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Promotion;
use App\Repository\PromotionRepositoryInterface;

class PromotionRepository extends EloquentRepository implements PromotionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Promotion::class;
    }

    public function attach(Promotion $promotion, $productId)
    {
        $promotion->products()->attach($productId);
    }

    public function detach(Promotion $promotion, $productId)
    {
        $promotion->products()->detach($productId);
    }

}
