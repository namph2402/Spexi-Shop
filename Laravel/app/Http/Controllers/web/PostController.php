<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostTagRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends RestController
{
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(
        PostRepositoryInterface         $repository,
        PostCategoryRepositoryInterface $categoryRepository,
        PostTagRepositoryInterface      $tagRepository
    )
    {
        parent::__construct($repository);
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        $limit = 5;
        $clause = [WhereClause::query('status', 1)];
        $orderBy = 'order:asc';
        $with = ['article', 'comments'];
        $categoryPosts = $this->categoryRepository->get($clause, $orderBy);
        $tagPosts = $this->tagRepository->get($clause, $orderBy);
        $posts = $this->repository->paginate($limit, $clause, $orderBy, $with);
        return view('posts.all', compact('posts', 'categoryPosts', 'tagPosts'));
    }

    public function search(Request $request)
    {
        $limit = 5;
        $clause = [WhereClause::query('status', 1), WhereClause::queryLike('name', $request->search)];
        $orderBy = 'order:asc';
        $with = ['article', 'comments'];

        $categoryPosts = $this->categoryRepository->get([WhereClause::query('status', 1)], $orderBy);
        $tagPosts = $this->tagRepository->get([WhereClause::query('status', 1)], $orderBy);
        $posts = $this->repository->paginate($limit, $clause, $orderBy, $with);
        return view('posts.search', compact('posts', 'categoryPosts', 'tagPosts'));
    }

    public function detail(Request $request, $category_slug, $slug)
    {
        $clause = [WhereClause::query('status', 1)];
        $orderBy = 'order:asc';
        $with = ['category','article', 'comments.author.profile', 'relateds.post'];

        $post = $this->repository->find([
            WhereClause::query('category_slug', $category_slug),
            WhereClause::query('slug', $slug),
            WhereClause::query('status', 1)
        ], null, $with);

        if (empty($post)) {
            return $this->errorNotFoundView();
        }

        $categoryPost = $this->repository->get([
            WhereClause::query('category_slug', $category_slug),
            WhereClause::queryDiff('id', $post->id),
            WhereClause::query('status', 1)
        ], $orderBy);
        return view('posts.detail', compact('post', 'categoryPost'));
    }
}
