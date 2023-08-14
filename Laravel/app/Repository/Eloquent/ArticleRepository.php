<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Article;
use App\Repository\ArticleRepositoryInterface;

class ArticleRepository extends EloquentRepository implements ArticleRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Article::class;
    }

}
