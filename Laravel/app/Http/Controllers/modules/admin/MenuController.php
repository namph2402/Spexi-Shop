<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Menu;
use App\Repository\MenuRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuController extends RestController
{
    public function __construct(MenuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = [];
        $withCount = [];
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('group_id')) {
            array_push($clauses, WhereClause::query('group_id', $request->group_id));
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
            'group_id' => 'required|numeric',
            'name' => 'required|max:255',
            'url' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'group_id',
            'name',
            'parent_id',
            'url',
            'icon'
        ]);

        $lastItem = $this->repository->find([WhereClause::query('group_id', $request->group_id)], 'order:desc');
        if ($lastItem) {
            $attributes['order'] = $lastItem->order + 1;
        }

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name'), WhereClause::query('group_id', $request->group_id))]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

        if($request->input('name') == 'Trang chủ') {
            $attributes['url'] = null;
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
        if (!($model instanceof Menu)) {
            return $this->errorNotFound();
        }

        $validator = $this->validateRequest($request, [
            'group_id' => 'nullable|numeric',
            'name' => 'nullable|max:255',
            'url' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'group_id',
            'name',
            'parent_id',
            'url',
            'icon'
        ]);

        $test_name = $this->repository->find([WhereClause::query('name', $request->input('name')), WhereClause::query('group_id', $request->group_id), WhereClause::queryDiff('id', $model->id)]);
        if ($test_name) {
            return $this->errorHad($request->input('name'));
        }

        if($request->input('name') == 'Trang chủ') {
            $attributes['url'] = null;
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
            $this->repository->bulkUpdate([WhereClause::query('order', $model->order, '>'), WhereClause::query('group_id', $model->group_id)], ['order' => DB::raw('`order` - 1')]);
            $this->repository->delete($model);
            DB::commit();
            return $this->success($model);
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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '<'), WhereClause::query('group_id', $model->group_id)], 'order:desc');

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

        $swapModel = $this->repository->find([WhereClause::query('order', $model->order, '>'), WhereClause::query('group_id', $model->group_id)], 'order:asc');

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
}
