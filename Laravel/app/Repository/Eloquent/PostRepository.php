<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Post;
use App\Repository\PostRepositoryInterface;

class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Post::class;
    }

    public function attach(Post $post, $tagId)
    {
        $post->tags()->attach($tagId);
    }

    public function detach(Post $post, $tagId)
    {
        $post->tags()->detach($tagId);
    }

}
