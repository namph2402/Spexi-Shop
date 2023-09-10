<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PostRepositoryInterface;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostTagRepositoryInterface;
use Illuminate\Http\Request;

class PostTagController extends RestController
{
    protected $postRepository;
    protected $categoryRepository;

    public function __construct(
        PostTagRepositoryInterface $repository,
        PostRepositoryInterface $postRepository,
        PostCategoryRepositoryInterface $categoryRepository
    )
    {
        parent::__construct($repository);
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function detail(Request $request, $slug)
    {
        $limit = 5;
        $clause = [WhereClause::query('status', 1)];
        $orderBy = 'order:asc';
        $with = ['article'];

        $tagPostMain = $this->repository->find([WhereClause::query('status', 1), WhereClause::query('slug',$slug)]);
        if (empty($tagPostMain)) {
            return $this->errorNotFoundView();
        }

        $tagPosts = $this->repository->get([WhereClause::query('status', 1), WhereClause::queryDiff('slug',$slug)]);
        $categoryPosts = $this->categoryRepository->get([WhereClause::query('status', 1)], $orderBy);

        $tagId = $tagPostMain->id;
        array_push($clause, WhereClause::queryRelationHas('tags', function ($q) use ($tagId) {
            $q->where('id', $tagId);
        }));
        
        $posts = $this->postRepository->paginate($limit, $clause, $orderBy, $with);
        return view('posts.tag', compact('posts', 'tagPostMain', 'tagPosts', 'categoryPosts'));
    }
}
