<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductImage;
use App\Repository\ProductImageRepositoryInterface;

class ProductImageRepository extends EloquentRepository implements ProductImageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductImage::class;
    }

}
