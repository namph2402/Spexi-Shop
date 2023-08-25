<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductTag;
use App\Repository\ProductTagRepositoryInterface;

class ProductTagRepository extends EloquentRepository implements ProductTagRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductTag::class;
    }

    public function attach(ProductTag $tag, $productId)
    {
        $tag->products()->attach($productId);
    }

    public function detach(ProductTag $tag, $productId)
    {
        $tag->products()->detach($productId);
    }
}
