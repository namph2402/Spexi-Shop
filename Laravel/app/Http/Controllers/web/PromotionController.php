<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\BannerRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductColorRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductSizeRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromotionController extends RestController
{
    protected $categoryRepository;
    protected $sizeRepository;
    protected $colorRepository;
    protected $promotionRepository;
    protected $bannerRepository;

    public function __construct(
        ProductRepositoryInterface         $repository,
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductSizeRepositoryInterface     $sizeRepository,
        ProductColorRepositoryInterface    $colorRepository,
        PromotionRepositoryInterface       $promotionRepository,
        BannerRepositoryInterface       $bannerRepository
    )
    {
        parent::__construct($repository);
        $this->categoryRepository = $categoryRepository;
        $this->colorRepository = $colorRepository;
        $this->sizeRepository = $sizeRepository;
        $this->promotionRepository = $promotionRepository;
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        $limit = 9;
        $clause = [WhereClause::query('status', 1)];
        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];
        $orderBy = 'order:asc';
        $with = [];

        array_push($clause, WhereClause::queryRelationHas('promotions', function ($q) use ($arrSize) {
            $q->whereIn('type', ['1','2']);
        }));

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
        
        $bannerPromotions = $this->bannerRepository->get([WhereClause::query('status', 1), WhereClause::queryRelationHas('group', function ($q) {
            $q->where('name', 'Promotion banner');
        })], 'order:asc');

        $promotions = $this->promotionRepository->get([WhereClause::query('status','1'), WhereClause::queryIn('type',['1','2'])]);
        $products = $this->repository->paginate($limit, $clause, $orderBy, $with);

        return view('promotions.all', compact('products', 'arrSize', 'arrColor', 'arrPrice', 'promotions', 'bannerPromotions'));
    }

    public function detail(Request $request, $slug)
    {
        $limit = 9;
        $clause = [WhereClause::query('status', 1)];
        $arrColor = [];
        $arrSize = [];
        $arrPrice = [];
        $arrProduct = [];
        $orderBy = 'order:asc';
        $with = ['promotions'];

        $promotionMain = $this->promotionRepository->find([WhereClause::query('slug', $slug),WhereClause::query('status', 1)], null, 'products');
        if (empty($promotionMain)) {
            return $this->errorNotFoundView();
        }

        if(count($promotionMain->products) > 0) {
            foreach($promotionMain->products as $p) {
                array_push($arrProduct, $p->id);
            }
        } else {
            array_push($arrProduct, 0);
        }

        array_push($clause, WhereClause::queryIn('id', $arrProduct));
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

        $promotions = $this->promotionRepository->get([WhereClause::query('status','1'), WhereClause::queryIn('type',['1','2']), WhereClause::queryDiff('id', $promotionMain->id)]);
        $products = $this->repository->paginate($limit, $clause, $orderBy, $with);

        return view('promotions.detail', compact('products', 'arrSize', 'arrColor', 'arrPrice', 'promotionMain', 'promotions'));
    }
}
