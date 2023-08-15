<?php

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\PostTag;

interface PostTagRepositoryInterface extends RepositoryInterface
{
    public function attach(PostTag $tag, $postId);
    public function detach(PostTag $tag, $postId);
}
