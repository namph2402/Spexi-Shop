<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\Enum\UnitName;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ShippingServiceRepositoryInterface;
use App\Repository\ShippingStoreRepositoryInterface;
use App\Repository\ShippingUnitRepositoryInterface;
use App\Utils\Logistics\GiaoHangNhanhUtil;
use App\Utils\Logistics\GiaoHangTietKiemUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required|max:255',
            'token' => 'required|max:255',
            'endpoint' => 'required|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'name',
            'username',
            'password',
            'token',
            'endpoint',
        ]);

        if ($request->name == UnitName::TIET_KIEM) {
            $attributes['logo'] = "https://docs.giaohangtietkiem.vn/images/logo.png";
            $attributes['class_name'] = "App\Utils\Logistics\GiaoHangTietKiemUtil";
        } else {
            if ($request->name == UnitName::NHANH) {
                $attributes['logo'] = "https://khachhang.ghn.vn/images/logo-ghn/logo-ghn.png";
                $attributes['class_name'] = "App\Utils\Logistics\GiaoHangNhanhUtil";
            } else {
                if ($request->name == UnitName::TU_GIAO) {
                    $attributes['class_name'] = "App\Utils\Logistics\GiaoHangTuGiaoUtil";
                }
            }
        }

        $name_test = $this->repository->find([WhereClause::query('name', $request->input('name'))]);
        if ($name_test) {
            return $this->errorClient('Đối tác đã tồn tại');
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

        $validator = $this->validateRequest($request, [
            'username' => 'nullable|max:255',
            'password' => 'nullable|max:255',
            'token' => 'nullable|max:255',
            'endpoint' => 'nullable|max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'username',
            'password',
            'token',
            'endpoint',
        ]);

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
            return $this->error('Not found');
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->delete($id, ['shipping_strores', 'shipping_services']);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function createUnitPartner(Request $request)
    {
        $unit = $this->repository->find([WhereClause::query('name', 'Tự giao')]);

        $validator = $this->validateRequest($request, [
            'store' => 'max:255',
            'service' => 'max:255',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributesStore['unit_id'] = $unit->id;
        $attributesStore['name'] = $request->store;
        $attributesStore['partner_id'] = rand(1, 1000000);
        $attributesStore['data'] = json_encode([]);
        $attributesStore['is_often'] = 1;

        $attributesService['unit_id'] = $unit->id;
        $attributesService['name'] = $request->service;
        $attributesService['code'] = rand(1, 1000000);
        $attributesService['data'] = json_encode([]);
        $attributesService['is_often'] = 1;

        try {
            DB::beginTransaction();
            if($request->store != null) {
                $this->shippingStore->create($attributesStore);
            }
            if($request->service != null) {
                $this->shippingService->create($attributesService);
            }
            DB::commit();
            return $this->success([]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function synchronized($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            if ($model->name == UnitName::NHANH) {
                $giaohangnhanhUtils = new GiaoHangNhanhUtil();
                $stores = $giaohangnhanhUtils->getStores();
                $services = $giaohangnhanhUtils->getServices();

                foreach ($stores as $item) {
                    if (!$this->shippingStore->find([WhereClause::query('partner_id', $item['partner_id'])])) {
                        $attributes['unit_id'] = $item['unit_id'];
                        $attributes['name'] = $item['name'];
                        $attributes['partner_id'] = $item['partner_id'];
                        $attributes['data'] = json_encode($item['data']);
                        $attributes['is_often'] = $item['is_often'];
                        $this->shippingStore->create($attributes);
                    }
                }
                foreach ($services as $item) {
                    if (!$this->shippingService->find([WhereClause::query('code', $item['code'])])) {
                        $attributes1['unit_id'] = $item['unit_id'];
                        $attributes1['name'] = $item['name'];
                        $attributes1['code'] = $item['code'];
                        $attributes1['data'] = json_encode($item['data']);
                        $attributes1['is_often'] = $item['is_often'];
                        $this->shippingService->create($attributes1);
                    }
                }
            }

            if ($model->name == UnitName::TIET_KIEM) {
                $giaohangntietkiemUtils = new GiaoHangTietKiemUtil();
                $stores = $giaohangntietkiemUtils->getStores();
                $services = $giaohangntietkiemUtils->getServices();
                foreach ($stores as $item) {
                    if (!$this->shippingStore->find([WhereClause::query('partner_id', $item['partner_id'])])) {
                        $attributes3['unit_id'] = $item['unit_id'];
                        $attributes3['name'] = $item['name'];
                        $attributes3['partner_id'] = $item['partner_id'];
                        $attributes3['data'] = json_encode($item['data']);
                        $attributes3['is_often'] = $item['is_often'];
                        $this->shippingStore->create($attributes3);
                    }
                }
                foreach ($services as $item) {
                    if (!$this->shippingService->find([WhereClause::query('code', $item['code'])])) {
                        $attributes4['unit_id'] = $item['unit_id'];
                        $attributes4['name'] = $item['name'];
                        $attributes4['code'] = $item['code'];
                        $attributes4['data'] = json_encode($item['data']);
                        $attributes4['is_often'] = $item['is_often'];
                        $this->shippingService->create($attributes4);
                    }
                }
            }
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorClient('Đồng bộ không thành công');
        }
    }
}
