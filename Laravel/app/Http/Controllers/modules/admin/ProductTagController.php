<?php

namespace App\Http\Controllers\modules\admin;

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
        $with = ['products'];
        $withCount = [];
        $tagClauses = [];
        $orderBy = $request->input('orderBy', 'order:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('product_id')) {
            $productId = $request->product_id;
            array_push($clauses, WhereClause::queryRelationHas('products', function ($q) use ($productId) {
                $q->where('id', $productId);
            }));
        }

        if ($request->has('product_id_add')) {
            $productIdAdd = $request->product_id_add;
            $tags = $this->repository->get([WhereClause::queryRelationHas('products', function ($q) use ($productIdAdd) {
                $q->where('id', $productIdAdd);
            })]);
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    array_push($tagClauses, $tag->id);
                }
                array_push($clauses, WhereClause::queryNotIn('id', $tagClauses));
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
            'name' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);

        $lastItem = $this->repository->find([], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name'))]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
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

    public function update(Request $request, $id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
        ]);
        $attributes['slug'] = Str::slug($attributes['name']);

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name')), WhereClause::queryDiff('id', $model->id)]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

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
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>')], ['order' => DB::raw('`order` - 1')]);
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
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
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
            $model = $this->repository->update($id,[
                'order' => $swapModel->order
            ]);
            $swapModel = $this->repository->update($swapModel->id,[
                'order' => $order
            ]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }


    public function attachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            foreach ($request->post_ids as $postId) {
                $this->repository->attach($model, $postId);
            };
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

    public function detachTags($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $postId = $request->post_ids;

        try {
            DB::beginTransaction();
            $this->repository->detach($model, $postId);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient($e->getMessage());
        }
    }

}
