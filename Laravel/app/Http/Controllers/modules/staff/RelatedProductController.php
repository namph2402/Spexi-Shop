<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductRepositoryInterface;
use App\Repository\RelatedProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RelatedProductController extends RestController
{
    protected $productRepository;

    public function __construct(RelatedProductRepositoryInterface $repository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [WhereClause::query('product_id', $request->product_id)];
        $with = ['product'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            $search = $request->search;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            }));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            array_push($clauses, WhereClause::queryRelationHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            }));
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
            'product_id' => 'required|numeric',
            'related_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'product_id',
            'related_id'
        ]);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->create($attributes);
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

            $items = $this->repository->get([WhereClause::query('order', $model->order, '>')]);
            foreach ($items as $item) {
                $this->repository->update($item, ['order' => $item->order - 1]);
            }

            $this->repository->delete($id);
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')], 'order:desc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update(
                $id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update(
                $swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')], 'order:asc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }
        try {
            DB::beginTransaction();
            $order = $model->order;
            $model = $this->repository->update(
                $id,
                ['order' => $swapModel->order]
            );
            $swapModel = $this->repository->update(
                $swapModel->id,
                ['order' => $order]
            );
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function loadProduct(Request $request) {
        $clauses = [WhereClause::queryDiff('id', $request->product_id)];
        $with = ['category'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');
        $arrId = [];

        $related = $this->repository->get([WhereClause::query('product_id', $request->product_id)], $orderBy, [], []);

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('category_id')) {
            array_push($clauses, WhereClause::query('category_id', $request->category_id));
        }

        if(count($related) > 0) {
            foreach($related as $r) {
                array_push($arrId, $r->related_id);
            }
            array_push($clauses, WhereClause::queryNotIn('id', $arrId));
        }
        $data = $this->productRepository->get($clauses, $orderBy, $with, $withCount);
        return $this->success($data);
    }
}
