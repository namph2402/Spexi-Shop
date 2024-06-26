<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Promotion;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PromotionController extends RestController
{
    protected $repository;
    protected $productRepository;

    public function __construct(PromotionRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($request->has('type')) {
            array_push($clauses, WhereClause::query('type', $request->type));
        }

        if ($request->has('createOrder')) {
            array_push($clauses, WhereClause::queryIn('type', ['3', '4']));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255|unique:promotions',
            'type' => 'required|numeric',
            'expired_date' => 'required|date'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'expired_date',
            'type',
        ]);

        $attributes['status'] = $request->status ? 1 : 0;
        $attributes['slug'] = Str::slug($attributes['name']);
        $attributes['discount_value'] = $request->input('discount_value', 0);
        $attributes['discount_percent'] = $request->input('discount_percent', 0);
        $attributes['min_order_value'] = $request->input('min_order_value', 0);
        $attributes['discount_same'] = $request->input('discount_same', 0);

        if ($request->hasFile('image')) {
            $attributes['image'] = FileStorageUtil::putFile('promotion', $request->file('image'));
        }

        if (strtotime($request->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
        }

        try {
            $model = $this->repository->create($attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            if (isset($attributes['banner'])) {
                FileStorageUtil::deleteFiles($attributes['banner']);
            }
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        $image_old = $model->image;

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255|unique:promotions,name,' . $id,
            'expired_date' => 'nullable|date'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'expired_date',
            'type',
        ]);

        $attributes['slug'] = Str::slug($attributes['name']);
        $attributes['status'] = $request->status ? 1 : 0;
        $attributes['discount_same'] = $request->input('discount_same', 0);
        $attributes['min_order_value'] = $request->input('min_order_value', 0);
        $attributes['discount_value'] = $request->input('discount_value', 0);
        $attributes['discount_percent'] = $request->input('discount_percent', 0);

        if ($request->hasFile('image')) {
            $attributes['image'] = FileStorageUtil::putFile('promotion', $request->file('image'));
        }

        if (strtotime($request->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
        }

        try {
            $promotion = $this->repository->update($id, $attributes);
            if ($promotion) {
                if ($promotion->type == Promotion::$DONG_GIA && $promotion->status == 1) {
                    $this->productRepository->bulkUpdate([
                        WhereClause::queryRelationHas('promotions', function ($q) use ($id) {
                            $q->where('id', $id);
                        }), WhereClause::query('price', $promotion->discount_same, '>')],
                        [
                            'sale_price' => $promotion->discount_same
                        ]);
                }
                if ($promotion->type == Promotion::$GIAM_SAN_PHAM && $promotion->status == 1) {
                    $percent = $promotion->discount_percent / 100;
                    $this->productRepository->bulkUpdate([
                        WhereClause::queryRelationHas('promotions', function ($q) use ($id) {
                            $q->where('id', $id);
                        }), WhereClause::query('price', $promotion->discount_value, '>')],
                        [
                            'sale_price' => DB::raw('`price` - (`price` * ' . $percent . ') - ' . $promotion->discount_value)
                        ]);
                }
            }
            if ($request->file('image') != '') {
                FileStorageUtil::deleteFiles($image_old);
            }
            return $this->success($promotion);
        } catch (\Exception $e) {
            Log::error($e);
            if (isset($attributes['banner'])) {
                FileStorageUtil::deleteFiles($attributes['banner']);
            }
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        $image = $model->image;

        try {
            $this->productRepository->bulkUpdate([
                WhereClause::queryRelationHas('promotions', function ($q) use ($id) {
                $q->where('id', $id);
            })],['sale_price' => DB::raw('`price`')]);
            $this->repository->delete($id,['mapping']);
            FileStorageUtil::deleteFiles($image);
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id, ['products']);
        $arrProduct = [];

        if (strtotime($model->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
        }

        if (count($model->products) > 0) {
            foreach ($model->products as $item) {
                array_push($arrProduct, $item->id);
            }

            if ($model->type == Promotion::$DONG_GIA) {
                $this->productRepository->bulkUpdate([WhereClause::queryIn('id', $arrProduct), WhereClause::query('price', $model->discount_same, '>')],
                    ['sale_price' => $model->discount_same]);
            }

            if ($model->type == Promotion::$GIAM_SAN_PHAM) {
                $this->productRepository->bulkUpdate([WhereClause::queryIn('id', $arrProduct), WhereClause::query('price', $model->discount_value, '>')],
                    ['sale_price' => DB::raw('`price` - '.$model->discount_value.' - `price` / 100 * '.$model->discount_percent)]);
            }
        }

        try {
            $model = $this->repository->update($id, ['status' => true]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = $this->repository->findById($id, ['products']);
        $arrProduct = [];
        if (count($model->products) > 0) {
            foreach ($model->products as $item) {
                array_push($arrProduct, $item->id);
            }
            $this->productRepository->bulkUpdate([WhereClause::queryIn('id', $arrProduct)],
                ['sale_price' => DB::raw('`price`')]
            );
        }

        try {
            $model = $this->repository->update($id, ['status' => false]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function loadProduct($id, Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['promotions'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        array_push($clauses, WhereClause::queryRelationHas('promotions', function ($q) use ($id) {
            $q->where('id', $id);
        }));

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('category_id')) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if ($limit) {
            $data = $this->productRepository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->productRepository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function attachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);

        $validator = $this->validateRequest($request, [
            'items' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            foreach ($request->items as $item) {
                if (count($item['promotions']) > 0) {
                    $promotion = $this->repository->findById($item['promotions']['0']['id']);
                    $this->repository->detach($promotion, $item['id']);
                }
                $this->repository->attach($model, $item['id']);

                if($model->status == 1) {
                    if ($model->type == Promotion::$DONG_GIA && $item['price'] > $model->discount_same) {
                        $this->productRepository->update($item['id'], ['sale_price' => $model->discount_same]);
                    }

                    if ($model->type == Promotion::$GIAM_SAN_PHAM && $item['price'] > $model->discount_value) {
                        $sale_price = $item['price'] - (($item['price'] * $model->discount_percent) / 100) - $model->discount_value;
                        $this->productRepository->update($item['id'], ['sale_price' => $sale_price]);
                    }
                } else {
                    $this->productRepository->update($item['id'], ['sale_price' => $item['price']]);
                }
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorClient($e->getMessage());
        }
    }

    public function detachProducts($id, Request $request)
    {
        $model = $this->repository->findById($id);

        $product = $this->productRepository->findById($request->product_id);

        try {
            $this->repository->detach($model, $request->product_id);
            $this->productRepository->update($request->product_id, ['sale_price' => $product->price]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorClient($e->getMessage());
        }
    }

    public function updateSalePrice($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model) || $model->type != Promotion::$GIAM_SAN_PHAM) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'sale_price_value' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            if ($model->status == 1) {
                $this->productRepository->update($request->id, ['sale_price' => $request->sale_price_value]);
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorClient($e->getMessage());
        }
    }
}
