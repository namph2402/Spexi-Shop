<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends RestController
{
    protected $productRepository;
    protected $tagRepository;

    public function __construct(ProductCategoryRepositoryInterface $repository, ProductRepositoryInterface $productRepository, ProductTagRepositoryInterface $tagRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
    }

    public function detail(Request $request, $slug)
    {
        $category = $this->repository->find([WhereClause::query('slug', $slug)], null, ['childrens.products']);
        if (empty($category)) {
            return $this->errorNotFoundView();
        }

        $arrCategory = [];
        if (count($category->childrens) == 0) {
            array_push($arrCategory, $category->id);
        } else {
            foreach ($category->childrens as $c) {
                array_push($arrCategory, $c->id);
            }
        }

        $limit = 9;
        $clause = [WhereClause::query('status', 1), WhereClause::queryIn('category_id', $arrCategory)];
        $orderBy = 'order:asc';
        $with = [];

        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];

        if ($request->has('color') && $request->color != 'All') {
            $arrColor = explode(",", $request->color);
            array_push($clause, WhereClause::queryRelationHas('warehouses', function ($q) use ($arrColor) {
                $q->whereIn('color_id', $arrColor);
            }));
        }

        if ($request->has('size') && $request->size != 'All') {
            $arrSize = explode(",", $request->size);
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
        $tags = $this->tagRepository->get([WhereClause::query('status', 1)],'order:asc');
        $products = $this->productRepository->paginate($limit, $clause, $orderBy, $with);
        return view('products.category', compact('products', 'category', 'arrSize', 'arrColor', 'arrPrice', 'tags'));
    }
}
