<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use Illuminate\Http\Request;

class ShipController extends RestController
{
    protected $districtRepository;
    protected $provinceRepository;

    public function __construct(
        ShippingFeeRepositoryInterface $repository,
        DistrictRepositoryInterface    $districtRepository,
        ProvinceRepositoryInterface    $provinceRepository
    )
    {
        parent::__construct($repository);
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function getDistricts($provinceId)
    {
        $province = $this->provinceRepository->findById($provinceId, ['districts']);
        return $this->success($province->districts);
    }

    public function getWards($districtId)
    {
        $district = $this->districtRepository->findById($districtId, ['wards']);
        return $this->success($district->wards);
    }

    public function getFee(Request $request)
    {
        $table = $this->repository->find([
            WhereClause::query('province_id', $request->province_id),
            WhereClause::query('district_id', $request->district_id),
            WhereClause::query('ward_id', $request->ward_id),
        ]);

        return $this->success(['fee' => $table ? $table->fee : 0]);
    }
}
