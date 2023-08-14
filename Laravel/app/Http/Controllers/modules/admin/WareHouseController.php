<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductColorRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductSizeRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseController extends RestController
{
    protected $productRepository;
    protected $sizeRepository;
    protected $colorRepository;

    public function __construct(
        WarehouseRepositoryInterface    $repository,
        ProductRepositoryInterface      $productRepository,
        ProductSizeRepositoryInterface  $sizeRepository,
        ProductColorRepositoryInterface $colorRepository
    )
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->sizeRepository = $sizeRepository;
        $this->colorRepository = $colorRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [WhereClause::query('product_id', $request->product_id)];
        $with = ['sizes', 'colors'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');
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
            'product_id' => 'required|numeric',
            'sizeArr' => 'required',
            'colorArr' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'product_id',
        ]);

        $product = $this->productRepository->findById($request->product_id);
        foreach ($request->sizeArr as $s) {
            $size = $this->sizeRepository->findById($s);
            foreach ($request->colorArr as $c) {
                $color = $this->colorRepository->findById($c);
                $attributes['code'] = $product->code . '-' . $color->name . '-' . $size->name;
                $attributes['size_id'] = $s;
                $attributes['color_id'] = $c;
                $attributes['weight'] = "0.2";
                $test_name = $this->repository->find([WhereClause::query('product_id', $request->product_id), WhereClause::query('size_id', $s), WhereClause::query('color_id', $c)]);
                if ($test_name) {
                    continue;
                } else {
                    try {
                        DB::beginTransaction();
                        $model = $this->repository->create($attributes);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error($e);
                        DB::rollBack();
                    }
                }
            }
        }
        return $this->success([]);
    }

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'weight' => 'nullable|numeric',
            'quantity' => 'nullable|max:255'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['weight'] = $request->input('weight', 0);
        $attributes['quantity'] = $request->input('quantity', 0);

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $this->repository->delete($id);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function enable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if($model->quantity <= 0) {
            return $this->errorClient('Sản phẩm đã hết hàng');
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, ['status' => true]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, ['status' => false]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
