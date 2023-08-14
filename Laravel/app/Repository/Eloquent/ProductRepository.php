<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Product;
use App\Repository\ProductRepositoryInterface;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Product::class;
    }

    public function attach(Product $product, $tagId)
    {
        $product->tags()->attach($tagId);
    }

    public function detach(Product $product, $tagId)
    {
        $product->tags()->detach($tagId);
    }

}
