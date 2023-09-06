<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\VoucherRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VoucherController extends RestController
{
    public function __construct(VoucherRepositoryInterface $repository)
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

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        } else {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('status', $request->status));
        }

        if ($request->has('type')) {
            array_push($clauses, WhereClause::query('type', $request->type));
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
            'code' => 'required|max:20',
            'type' => 'required|numeric',
            'quantity' => 'required|numeric',
            'expired_date' => 'required|date',
            'min_order_value' => 'required|numeric',

        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'code',
            'quantity',
            'expired_date',
            'type',
            'private',
            'status',
            'min_order_value'
        ]);
        $attributes['remain_quantity'] = $request->quantity;
        $attributes['discount_value'] = $request->input('discount_value', 0);
        $attributes['discount_percent'] = $request->input('discount_percent', 0);

        $name_test = $this->repository->find([WhereClause::query('code', $request->code)]);
        if ($name_test) {
            return $this->errorClient('Mã voucher đã tồn tại');
        }

        if (strtotime($request->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
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
            'code' => 'nullable|max:20',
            'quantity' => 'nullable|numeric',
            'expired_date' => 'nullable|date',
            'type' => 'nullable|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'code',
            'quantity',
            'expired_date',
            'type',
            'status',
            'private',
            'min_order_value'
        ]);

        $oldQuantity = $model->quantity - $model->remain_quantity;
        $newQuantity = $request->quantity - $oldQuantity;
        if ($newQuantity <= 0) {
            $newQuantity = 0;
        }

        $attributes['remain_quantity'] = $newQuantity;
        $attributes['discount_value'] = $request->input('discount_value', 0);
        $attributes['discount_percent'] = $request->input('discount_percent', 0);

        $name_test = $this->repository->find([WhereClause::query('code', $request->code), WhereClause::queryDiff('id', $id)]);
        if ($name_test) {
            return $this->errorClient('Mã voucher đã tồn tại');
        }

        if (strtotime($request->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
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
        if (strtotime($model->expired_date) < strtotime("now")) {
            return $this->errorClient('Thời gian hết hạn không đúng');
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
