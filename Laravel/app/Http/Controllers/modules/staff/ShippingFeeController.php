<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;

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

}
