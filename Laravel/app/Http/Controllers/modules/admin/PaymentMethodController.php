<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\PaymentMethodRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::queryLike('status', $request->status));
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
            'name' => 'required|unique:payment_methods',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name'
        ]);
        $attributes['config'] = null;

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

        if ($model->name == "Momo") {
            $validator = $this->validateRequest($request, [
                'endpoint' => 'required',
                'partnerCode' => 'required',
                'accessKey' => 'required',
                'secretKey' => 'required',
                'type' => 'required',
            ]);
            if ($validator) {
                return $this->errorClient($validator);
            }
            $config = [
                "mm_endpoint" => $request->endpoint,
                "mm_partnerCode" => $request->partnerCode,
                "mm_accessKey" => $request->accessKey,
                "mm_secretKey" => $request->secretKey,
                "mm_type" => $request->type,
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
