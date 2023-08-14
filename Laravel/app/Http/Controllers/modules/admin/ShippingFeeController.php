<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShippingFeeController extends RestController
{
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;

    public function __construct(
        ShippingFeeRepositoryInterface $repository,
        ProvinceRepositoryInterface    $provinceRepository,
        DistrictRepositoryInterface    $districtRepository,
        WardRepositoryInterface        $wardRepository
    )
    {
        parent::__construct($repository);
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'province_id:asc');

        if ($request->has('province_id')) {
            array_push($clauses, WhereClause::query('province_id', $request->province_id));
        }
        if ($request->has('district_id')) {
            array_push($clauses, WhereClause::query('district_id', $request->district_id));
        }

        if ($request->has('ward_id')) {
            array_push($clauses, WhereClause::query('ward_id', $request->ward_id));
        }

        $with = ['province', 'district', 'ward'];
        $withCount = [];

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }


    public function store(Request $request)
    {
        $clauses = [];

        $validator = $this->validateRequest($request, [
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
            'fee' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        if ($request->has('ward_id')) {
            array_push($clauses, WhereClause::query('ward_id', $request->ward_id));
        }

        if ($request->has('district_id')) {
            array_push($clauses, WhereClause::query('district_id', $request->district_id));
        }

        if ($request->has('province_id')) {
            array_push($clauses, WhereClause::query('province_id', $request->province_id));
        }

        $attributes = $request->only([
            'province_id',
            'district_id',
            'ward_id',
            'fee'
        ]);

        $model = $this->repository->find($clauses);

        try {
            DB::beginTransaction();
            if (empty($model)) {
                $item = $this->repository->create($attributes);
            } else {
                $item = $this->repository->update($model->id, $attributes);
            }
            DB::commit();
            return $this->success($item);
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
            'fee' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $request->only(['fee']));
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
