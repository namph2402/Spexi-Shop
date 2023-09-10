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
        if (empty($province)) {
            return [];
        }
        $districts = [];
        foreach ($province->districts as $district) {
            array_push($districts, [
                'id' => $district->id,
                'name' => $district->name,
            ]);
        }
        return $this->success($districts);
    }

    public function getWards($districtId)
    {
        $district = $this->districtRepository->findById($districtId, ['wards']);
        if (empty($district)) {
            return [];
        }
        $wards = [];

        foreach ($district->wards as $ward) {
            array_push($wards, [
                'id' => $ward->id,
                'name' => $ward->name,
            ]);
        }

        return $this->success($wards);
    }

    public function getFee(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }
        
        $table = $this->repository->find([
            WhereClause::query('province_id', $request->province_id),
            WhereClause::query('district_id', $request->district_id),
            WhereClause::query('ward_id', $request->ward_id),
        ]);

        if (empty($table)) {
            return $this->errorClient('Không có biểu phí');
        } else {
            return $this->success(['fee' => $table->fee]);
        }
    }
}
