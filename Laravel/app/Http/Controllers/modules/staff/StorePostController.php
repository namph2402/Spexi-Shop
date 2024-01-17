<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\StorePostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StorePostController extends RestController
{
    public function __construct(StorePostRepositoryInterface $repository)
    {
        parent::__construct($repository);
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
            'name' => 'required|max:255|unique:store_posts,name',
            'content' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'content',
        ]);

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
            'name' => 'nullable|max:255|unique:store_posts,name,' . $id,
            'content' => 'nullable',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'content',
        ]);

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
        try {
            $this->repository->delete($id);
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

}
