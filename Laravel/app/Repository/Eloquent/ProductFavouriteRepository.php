<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductFavourite;
use App\Repository\ProductFavouriteRepositoryInterface;

class ProductFavouriteRepository extends EloquentRepository implements ProductFavouriteRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductFavourite::class;
    }

    public function attach(ProductFavourite $product, $tagId)
    {
        $product->tags()->attach($tagId);
    }

    public function detach(ProductFavourite $product, $tagId)
    {
        $product->tags()->detach($tagId);
    }

}
