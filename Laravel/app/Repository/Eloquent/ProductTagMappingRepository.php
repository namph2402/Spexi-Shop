<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ProductTagMapping;
use App\Repository\ProductTagMappingRepositoryInterface;

class ProductTagMappingRepository extends EloquentRepository implements ProductTagMappingRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductTagMapping::class;
    }

}
