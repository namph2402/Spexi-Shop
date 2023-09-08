<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use App\Utils\OfficeUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProvinceController extends RestController
{
    protected $districtRepository;
    protected $wardRepository;
    protected $feeRepository;

    public function __construct(ProvinceRepositoryInterface $repository, DistrictRepositoryInterface $districtRepository, WardRepositoryInterface $wardRepository, ShippingFeeRepositoryInterface $feeRepository)
    {
        parent::__construct($repository);
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->feeRepository = $feeRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['districts.wards'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if($request->has('type') && $request->type == 'ward') {
            if ($request->has('search')) {
                $search = $request->search;
                array_push($clauses, WhereClause::orQuery([
                    WhereClause::queryLike('name', $request->search),
                    WhereClause::queryRelationHas('province', function ($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                    }),
                    WhereClause::queryRelationHas('district', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                })
                ]));
            }
            $data = $this->wardRepository->paginate($limit, $clauses, $orderBy, ['district','province'], $withCount);
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

                    $province = $this->repository->find([WhereClause::query('name', $provinceValue)]);
                    if(!$province) {
                        $province = $this->repository->create([
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

                    $fee = $this->feeRepository->find([WhereClause::query('province_id', $province->id), WhereClause::query('district_id', $district->id), WhereClause::query('ward_id', $ward->id)]);
                    if(!$fee) {
                        $fee = $this->feeRepository->create([
                            'province_id' => $province->id,
                            'district_id' => $district->id,
                            'ward_id' => $ward->id,
                            'fee' => 30000
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
            DB::beginTransaction();
            $this->repository->truncate();
            $this->districtRepository->truncate();
            $this->wardRepository->truncate();
            $this->feeRepository->truncate();
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
