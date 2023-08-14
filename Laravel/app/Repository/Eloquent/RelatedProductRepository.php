<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\RelatedProduct;
use App\Repository\RelatedProductRepositoryInterface;

class RelatedProductRepository extends EloquentRepository implements RelatedProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return RelatedProduct::class;
    }

}
