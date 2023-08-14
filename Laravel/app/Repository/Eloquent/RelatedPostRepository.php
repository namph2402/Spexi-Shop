<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\RelatedPost;
use App\Repository\RelatedPostRepositoryInterface;

class RelatedPostRepository extends EloquentRepository implements RelatedPostRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return RelatedPost::class;
    }

}
