<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Comment;
use App\Repository\CommentRepositoryInterface;

class CommentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Comment::class;
    }

}
