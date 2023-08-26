<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductColorRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductSizeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends RestController
{
    protected $categoryRepository;
    protected $sizeRepository;
    protected $colorRepository;

    public function __construct(
        ProductRepositoryInterface         $repository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductSizeRepositoryInterface     $sizeRepository,
        ProductColorRepositoryInterface    $colorRepository
    )
    {
        parent::__construct($repository);
        $this->categoryRepository = $categoryRepository;
        $this->colorRepository = $colorRepository;
        $this->sizeRepository = $sizeRepository;
    }

    public function index(Request $request)
    {
        $limit = 9;
        $clause = [WhereClause::query('status', 1)];
        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];
        $orderBy = 'order:asc';
        $with = ['comments'];
        if ($request->has('color') && $request->color != 'All') {
            $colors = explode(",", $request->color);
            foreach ($colors as $color) {
                array_push($arrColor, $color);
            }
            array_push($clause, WhereClause::queryRelationHas('warehouses', function ($q) use ($arrColor) {
                $q->whereIn('color_id', $arrColor);
            }));
        }
        if ($request->has('size') && $request->size != 'All') {
            $sizes = explode(",", $request->size);
            foreach ($sizes as $size) {
                array_push($arrSize, $size);
            }
            array_push($clause, WhereClause::queryRelationHas('warehouses', function ($q) use ($arrSize) {
                $q->whereIn('size_id', $arrSize);
            }));
        }
        if (Str::length($request->priceFrom) > 0) {
            array_push($clause, WhereClause::query('sale_price', $request->priceFrom, '>='));
        }
        if (Str::length($request->priceTo) > 0) {
            array_push($clause, WhereClause::query('sale_price', $request->priceTo, '<='));
        }
        $products = $this->repository->paginate($limit, $clause, $orderBy, $with);
        return view('products.all', compact('products', 'arrSize', 'arrColor', 'arrPrice'));
    }

    public function search(Request $request)
    {
        $limit = 9;
        $clause = [WhereClause::query('status', 1), WhereClause::queryLike('name', $request->search)];
        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];
        $orderBy = 'order:asc';
        $with = ['comments'];
        if ($request->has('color') && $request->color != 'All') {
            $colors = explode(",", $request->color);
            foreach ($colors as $color) {
                array_push($arrColor, $color);
            }
            array_push($clause, WhereClause::queryRelationHas('warehouses', function ($q) use ($arrColor) {
                $q->whereIn('color_id', $arrColor);
            }));
        }
        if ($request->has('size') && $request->size != 'All') {
            $sizes = explode(",", $request->size);
            foreach ($sizes as $size) {
                array_push($arrSize, $size);
            }
            array_push($clause, WhereClause::queryRelationHas('warehouses', function ($q) use ($arrSize) {
                $q->whereIn('size_id', $arrSize);
            }));
        }
        if (Str::length($request->priceFrom) > 0) {
            array_push($clause, WhereClause::query('sale_price', $request->priceFrom, '>='));
        }
        if (Str::length($request->priceTo) > 0) {
            array_push($clause, WhereClause::query('sale_price', $request->priceTo, '<='));
        }
        $products = $this->repository->paginate($limit, $clause, $orderBy, $with);
        return view('products.search', compact('products', 'arrSize', 'arrColor', 'arrPrice'));
    }

    public function detail(Request $request, $category_slug, $slug)
    {
        $arrColor = [];
        $arrSize = [];
        $with = ['category', 'images', 'warehouseViews', 'article', 'comments.author.profile', 'relateds.product.comments'];
        $category = $this->repository->get([WhereClause::query('category_slug', $category_slug), WhereClause::queryDiff('slug', $slug), WhereClause::query('status', 1)], null, ['comments']);
        $product = $this->repository->find([
            WhereClause::query('category_slug', $category_slug),
            WhereClause::query('slug', $slug),
            WhereClause::query('status', 1)
        ], null, $with);
        if (empty($product) || count($product->warehouseViews) == 0) {
            return $this->errorNotFoundView();
        }
        foreach ($product->warehouseViews as $item) {
            $arrSize[$item->size_id] = $item->size_id;
            $arrColor[$item->color_id] = $item->color_id;
        }
        $sizePs = $this->sizeRepository->get([WhereClause::queryIn('id', $arrSize)]);
        $colorPs = $this->colorRepository->get([WhereClause::queryIn('id', $arrColor)]);
        return view('products.detail', compact('product', 'category', 'sizePs', 'colorPs'));
    }
}
