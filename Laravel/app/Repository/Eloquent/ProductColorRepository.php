<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductColor;
use App\Repository\ProductColorRepositoryInterface;

class ProductColorRepository extends EloquentRepository implements ProductColorRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductColor::class;
    }

}
