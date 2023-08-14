<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PostTagMapping;
use App\Repository\PostTagMappingRepositoryInterface;

class PostTagMappingRepository extends EloquentRepository implements PostTagMappingRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PostTagMapping::class;
    }

}
