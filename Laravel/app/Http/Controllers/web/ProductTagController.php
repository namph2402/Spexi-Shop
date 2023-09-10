<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductTagController extends RestController
{
    protected $productRepository;

    public function __construct(
        ProductTagRepositoryInterface $repository,
        ProductRepositoryInterface $productRepository
    )
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function detail(Request $request, $slug)
    {
        $tag = $this->repository->find([WhereClause::query('slug', $slug)]);
        if (empty($tag)) {
            return $this->errorNotFoundView();
        }

        $tagId = $tag->id;
        $limit = 9;
        $orderBy = 'order:asc';
        $with = [];

        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];

        $clause = [WhereClause::query('status', 1), WhereClause::queryRelationHas('tags', function ($q) use ($tagId) {
            $q->where('id', $tagId);
        })];

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

        $products = $this->productRepository->paginate($limit, $clause, $orderBy, $with);
        return view('products.tag', compact('products', 'tag', 'arrSize', 'arrColor', 'arrPrice'));
    }
}
