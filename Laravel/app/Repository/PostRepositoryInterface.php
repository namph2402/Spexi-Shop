<?php

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Post;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function attach(Post $post, $tagId);
    public function detach(Post $post, $tagId);
}
