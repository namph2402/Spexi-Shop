<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PostCategory;
use App\Repository\PostCategoryRepositoryInterface;

class PostCategoryRepository extends EloquentRepository implements PostCategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PostCategory::class;
    }

}
