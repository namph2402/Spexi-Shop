<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\MenuGroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuGroupController extends RestController
{
    public function __construct(MenuGroupRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['menus.parent'];
        $withCount = [];
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

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
            'name' => 'required|max:255|unique:menu_groups',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['name'] = $request->name;

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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'name' => 'nullable|max:255|unique:menu_groups,name,' . $id,
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes['name'] = $request->name;

        try {
            $model = $this->repository->update($model, $attributes);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id, ['menus']);
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
