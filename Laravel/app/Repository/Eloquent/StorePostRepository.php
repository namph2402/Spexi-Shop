<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\StorePost;
use App\Repository\StorePostRepositoryInterface;

class StorePostRepository extends EloquentRepository implements StorePostRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return StorePost::class;
    }

}
