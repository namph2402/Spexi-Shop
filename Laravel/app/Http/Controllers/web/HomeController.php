<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\BannerRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Repository\StorePostRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends RestController
{
    protected $bannerRepository;
    protected $categoryRepository;
    protected $tagRepository;
    protected $promotionRepository;
    protected $storePostRepository;
    protected $postRepository;

    public function __construct(
        ProductRepositoryInterface         $repository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductTagRepositoryInterface      $tagRepository,
        PromotionRepositoryInterface       $promotionRepository,
        BannerRepositoryInterface          $bannerRepository,
        PostRepositoryInterface            $postRepository,
        StorePostRepositoryInterface       $storePostRepository)
    {
        parent::__construct($repository);
        $this->bannerRepository = $bannerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->promotionRepository = $promotionRepository;
        $this->storePostRepository = $storePostRepository;
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        $bannerMains = $this->bannerRepository->get([WhereClause::query('status', 1), WhereClause::queryRelationHas('group', function ($q) {
            $q->where('name', 'Main banner');
        })], 'order:asc');

        $bannerSubs = $this->bannerRepository->get([WhereClause::query('status', 1), WhereClause::queryRelationHas('group', function ($q) {
            $q->where('name', 'Sub banner');
        })], 'order:asc');

        $categories = $this->categoryRepository->get([WhereClause::query('parent_id', 0)], 'order:asc', ['childrens.products.warehouses', 'products.warehouses']);

        $tagHot = $this->tagRepository->find([WhereClause::query('name', 'Sản phẩm hot')]);
        $featured = $this->repository->paginate(8,[
            WhereClause::query('status', 1),
            WhereClause::queryRelationHas('tags', function ($q) {$q->where('name', 'Sản phẩm hot');})
        ], 'order:asc');

        $tagNew = $this->tagRepository->find([WhereClause::query('name', 'Sản phẩm hot')]);
        $recent = $this->repository->paginate(8,[
            WhereClause::query('status', 1),
            WhereClause::queryRelationHas('tags', function ($q) {$q->where('name', 'Sản phẩm mới');})
        ], 'order:asc');

        $posts = $this->postRepository->paginate(4,[WhereClause::query('status', 1)],'created_at:desc');

        $promotions = $this->promotionRepository->paginate(2, [WhereClause::query('status', 1), WhereClause::queryIn('type',['1','2'])]);
        return view('pages.index', compact('bannerMains', 'bannerSubs', 'categories', 'tagHot', 'featured', 'promotions', 'tagNew', 'recent', 'posts'));
    }

    public function privacy() {
        $title = "Chính sách bảo mật";
        $post = $this->storePostRepository->find([WhereClause::query('name', $title)]);
        return view('pages.storePost',compact('title', 'post'));
    }

    public function purchase() {
        $title = "Chính sách mua hàng";
        $post = $this->storePostRepository->find([WhereClause::query('name', $title)]);
        return view('pages.storePost',compact('title', 'post'));
    }
    public function size() {
        $title = "Hướng dẫn chọn size";
        $post = $this->storePostRepository->find([WhereClause::query('name', $title)]);
        return view('pages.storePost',compact('title', 'post'));
    }
}
