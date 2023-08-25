<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PromotionProductMappingRepositoryInterface;
use Illuminate\Http\Request;

class PromotionProductMappingController extends RestController
{
    protected $productRepository;

    public function __construct(PromotionProductMappingRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['product.category'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'promotion_id:asc');

        if ($request->has('promotion_id')) {
            array_push($clauses, WhereClause::query('promotion_id', $request->promotion_id));
        }

        if ($request->has('search')) {
            $search = $request->search;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($search) {
                $q->where('name', $search);
            }));
        }

        if ($request->has('category_id')) {
            $category_id = $request->category_id;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            }));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function loadProduct()
    {
        $arrID = [];
        $with = [];
        $withCount = [];
        $orderBy = 'order:asc';
        $promotion = $this->repository->get([], 'promotion_id:asc');
        if (count($promotion) > 0) {
            foreach ($promotion as $p) {
                array_push($arrID, $p->product_id);
            }
            $data = $this->productRepository->get([WhereClause::queryNotIn('id', $arrID)], $orderBy, $with, $withCount);
        } else {
            $data = $this->productRepository->get([], $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }
}
