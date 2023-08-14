<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostTagRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostCategoryController extends RestController
{
    protected $postRepository;
    protected $tagRepository;

    public function __construct(
        PostCategoryRepositoryInterface $repository,
        PostRepositoryInterface $postRepository,
        PostTagRepositoryInterface $tagRepository
    )
    {
        parent::__construct($repository);
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    public function detail(Request $request, $slug)
    {
        $limit = 5;
        $clause = [WhereClause::query('status', 1), WhereClause::query('category_slug',$slug)];
        $orderBy = 'order:asc';
        $with = ['article'];
        $categoryPostMain = $this->repository->find([WhereClause::query('status', 1), WhereClause::query('slug',$slug)]);
        if (empty($categoryPostMain)) {
            return $this->errorNotFoundView();
        }
        $categoryPosts = $this->repository->get([WhereClause::query('status', 1), WhereClause::queryDiff('slug',$slug)]);
        $tagPosts = $this->tagRepository->get([WhereClause::query('status', 1)], $orderBy);
        $posts = $this->postRepository->paginate($limit, $clause, $orderBy, $with);
        return view('posts.category', compact('posts', 'categoryPostMain', 'categoryPosts', 'tagPosts'));
    }
}
