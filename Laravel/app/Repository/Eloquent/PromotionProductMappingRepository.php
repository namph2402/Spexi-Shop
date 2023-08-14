<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PromotionProductMapping;
use App\Repository\PromotionProductMappingRepositoryInterface;

class PromotionProductMappingRepository extends EloquentRepository implements PromotionProductMappingRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PromotionProductMapping::class;
    }

}
