<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\PaymentMethod;
use App\Repository\PaymentMethodRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentMethodController extends RestController
{

    public function __construct(PaymentMethodRepositoryInterface $repository)
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
            'name' => ['required'],
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        $attributes = $request->only([
            'name',
        ]);
        $attributes['config'] = null;
        $name_test = $this->repository->get([WhereClause::query('name', $request->input('name'))])->first();
        if ($name_test) {
            return $this->errorClient('Cấu hình thanh toán đã tồn tại');
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
        if (!($model instanceof PaymentMethod)) {
            return $this->errorNotFound();
        }
        $attributes = [];
        if ($model->name == "VNPay") {
            $validator = $this->validateRequest($request, [
                'vnp_Url' => 'required',
                'vnp_TmnCode' => 'required',
                'vnp_HashSecret' => 'required',
                'vnp_Locale' => 'required',
                'vnp_Version' => 'required',
            ]);
            if ($validator) {
                return $this->errorClient($validator);
            }
            $config = [
                "vnp_Url" => $request->vnp_Url,
                "vnp_TmnCode" => $request->vnp_TmnCode,
                "vnp_HashSecret" => $request->vnp_HashSecret,
                "vnp_Locale" => $request->vnp_Locale,
                "vnp_Version" => $request->vnp_Version,
            ];
            $attributes['config'] = json_encode($config, true);
        }
        if ($model->name == "Chuyển khoản") {
            $validator = $this->validateRequest($request, [
                'owner_name' => 'required',
                'bank_name' => 'required',
                'bank_account' => 'required|numeric',
            ]);
            if ($validator) {
                return $this->errorClient($validator);
            }
            $config = [
                "accounts" => [
                    [
                        "owner_name" => $request->owner_name,
                        "bank_name" => $request->bank_name,
                        "bank_account" => $request->bank_account,
                        "bank_branch" => $request->bank_branch
                    ]
                ]
            ];
            $attributes['config'] = json_encode($config, true);
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

    public function destroyConfig($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }
        if (!($model instanceof PaymentMethod)) {
            return $this->errorNotFound();
        }
        $attributes['config'] = null;
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
        $model = (new PaymentMethod())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success([]);
    }
}
