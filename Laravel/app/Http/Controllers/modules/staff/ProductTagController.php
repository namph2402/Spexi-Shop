<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ProductTagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductTagController extends RestController
{
    protected $repository;

    public function __construct(ProductTagRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('product_id')) {
            $id = $request->product_id;
            array_push($clauses, WhereClause::queryRelationHas('products', function ($q) use ($id) {
                $q->where('id', $id);
            }));
        }

        if ($request->has('product_id_add')) {
            $idAdd = $request->product_id_add;
            $tags = $this->repository->pluck([WhereClause::queryRelationHas('products', function ($q) use ($idAdd) {
                $q->where('id', $idAdd);
            })], 'id');

            if (count($tags) > 0) {
                array_push($clauses, WhereClause::queryNotIn('id', $tags));
            }
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
            'name' => 'required|max:255|unique:product_tags',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['name'] = $request->name;
        $attributes['slug'] = Str::slug($attributes['name']);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        try {
            $model = $this->repository->create($attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255|unique:product_tags,name,' . $id,
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['name'] = $request->name;
        $attributes['slug'] = Str::slug($attributes['name']);

        try {
            $model = $this->repository->update($id, $attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);

        try {
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>')], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($id);
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }


    public function enable($id)
    {
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
        try {
            $model = $this->repository->update($id, ['status' => false]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function up($id)
    {
        $model = $this->repository->findById($id);

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<')], 'order:desc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể tăng thứ hạng');
        }

        try {
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function down($id)
    {
        $model = $this->repository->findById($id);

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>')], 'order:asc');
        if (empty($swapModel)) {
            return $this->errorClient('Không thể giảm thứ hạng');
        }

        try {
            $order = $model->order;
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }


    public function attachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);

        try {

            foreach ($request->product_ids as $id) {
                $this->repository->attach($model, $id);
            };
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorClient($e->getMessage());
        }
    }

    public function detachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);

        try {
            $this->repository->detach($model, $request->product_ids);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->errorClient($e->getMessage());
        }
    }

}
