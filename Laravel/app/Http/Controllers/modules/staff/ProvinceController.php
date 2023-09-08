<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use Illuminate\Http\Request;

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

}
