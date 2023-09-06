<?php

namespace App\Http\Controllers\modules\staff;

use App\Http\Controllers\RestController;
use App\Repository\ShippingServiceRepositoryInterface;
use App\Repository\ShippingStoreRepositoryInterface;
use App\Repository\ShippingUnitRepositoryInterface;
use Illuminate\Http\Request;

class ShippingUnitController extends RestController
{
    protected $shippingStore;
    protected $shippingService;

    public function __construct(
        ShippingUnitRepositoryInterface $repository,
        ShippingStoreRepositoryInterface $shippingStore,
        ShippingServiceRepositoryInterface $shippingService
    ) {
        parent::__construct($repository);
        $this->shippingStore = $shippingStore;
        $this->shippingService = $shippingService;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['shipping_strores', 'shipping_services'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }
}
