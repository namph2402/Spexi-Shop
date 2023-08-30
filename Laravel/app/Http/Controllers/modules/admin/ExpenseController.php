<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ExpenseRepositoryInterface;
use App\Utils\AuthUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExpenseController extends RestController
{
    public function __construct(ExpenseRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $month = $request->input('month', date("m"));
        $year = $request->input('year', date("Y"));
        $limit = $request->input('limit', null);
        $clauses = [WhereClause::queryMonth('date_created', $month), WhereClause::queryYear('date_created', $year)];
        $with = [];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'date_created:desc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('name', $request->search), WhereClause::queryLike('creator_name', $request->search)]));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
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
        $user = AuthUtil::getInstance()->getModel();

        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'amount' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'description',
            'amount',
        ]);

        $attributes['creator_id'] = $user->id;
        $attributes['creator_name'] = $user->name;
        $attributes['date_created'] = $request->input('date_created', Carbon::now());

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
            'amount' => 'nullable|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'description',
            'amount',
            'date_created'
        ]);

        if($attributes['date_created'] = '0000-00-00 00:00:00') {
            $attributes['date_created'] = Carbon::now();
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
            $this->repository->delete($id);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function confirm($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
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
}
