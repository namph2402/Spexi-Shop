<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;
use App\Utils\OfficeUtil;
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

    public function import(Request $request)
    {
        set_time_limit(0);
        $validator = $this->validateRequest($request, [
            'file' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $file = $request->file('file');
        if ($file->getClientOriginalExtension() != 'xlsx') {
            return $this->errorClient('Không đúng định dạng file .xlsx');
        }

        $newData = OfficeUtil::readXLSX($file->getRealPath(), 0, 2, 'A', -1, 'C');

        if (!empty($newData)) {
            try {
                DB::beginTransaction();
                foreach ($newData as $row) {
                    $provinceValue = trim($row[0]);
                    $districtValue = trim($row[1]);
                    $wardValue = trim($row[2]);

                    if($provinceValue == null || $districtValue == null || $wardValue == null) {
                        continue;
                    }

                    $province = $this->provinceRepository->find([WhereClause::query('name', $provinceValue)]);
                    if(!$province) {
                        $province = $this->provinceRepository->create([
                            'name' => $provinceValue
                        ]);
                    }

                    $district = $this->districtRepository->find([WhereClause::query('province_id', $province->id), WhereClause::query('name', $districtValue)]);
                    if(!$district) {
                        $district = $this->districtRepository->create([
                            'province_id' => $province->id,
                            'name' => $districtValue
                        ]);
                    }

                    $ward = $this->wardRepository->find([WhereClause::query('province_id', $province->id), WhereClause::query('district_id', $district->id), WhereClause::query('name', $wardValue)]);
                    if(!$ward) {
                        $ward = $this->wardRepository->create([
                            'province_id' => $province->id,
                            'district_id' => $district->id,
                            'name' => $wardValue
                        ]);
                    }

                    $fee = $this->repository->find([WhereClause::query('province_id', $province->id), WhereClause::query('district_id', $district->id), WhereClause::query('ward_id', $ward->id)]);
                    if(!$fee) {
                        $fee = $this->repository->create([
                            'province_id' => $province->id,
                            'district_id' => $district->id,
                            'ward_id' => $ward->id,
                            'fee' => 25000
                        ]);
                    }
                }
                DB::commit();
                return $this->success([]);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return $this->error($e->getMessage());
            }
            sleep(0.5);
        }
    }

    public function truncate() {
        try {
            $this->provinceRepository->truncate();
            $this->districtRepository->truncate();
            $this->wardRepository->truncate();
            $this->repository->truncate();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, [
            'fee' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        try {
            $model = $this->repository->update($id, $request->only(['fee']));
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error($e->getMessage());
        }
    }
}
