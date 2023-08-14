<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductSize;
use App\Repository\ProductSizeRepositoryInterface;

class ProductSizeRepository extends EloquentRepository implements ProductSizeRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductSize::class;
    }

}
