<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\Enum\UnitName;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Order;
use App\Models\OrderShip;
use App\Repository\OrderRepositoryInterface;
use App\Repository\OrderShipRepositoryInterface;
use App\Repository\ShippingServiceRepositoryInterface;
use App\Repository\ShippingStoreRepositoryInterface;
use App\Repository\ShippingUnitRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use App\Utils\GiaoHangUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderShipController extends RestController
{
    protected $orderRepository;
    protected $unitRepository;
    protected $storeRepository;
    protected $serviceRepository;
    protected $warehouseRepository;

    public function __construct(
        OrderShipRepositoryInterface       $repository,
        OrderRepositoryInterface           $orderRepository,
        ShippingUnitRepositoryInterface    $unitRepository,
        ShippingStoreRepositoryInterface   $storeRepository,
        ShippingServiceRepositoryInterface $serviceRepository,
        WarehouseRepositoryInterface       $warehouseRepository
    )
    {
        parent::__construct($repository);
        $this->orderRepository = $orderRepository;
        $this->unitRepository = $unitRepository;
        $this->storeRepository = $storeRepository;
        $this->serviceRepository = $serviceRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [WhereClause::queryDiff('order_status', Order::$LEN_DON), WhereClause::queryDiff('order_status', Order::$XAC_NHAN)];
        $with = ['details.product','shipping.unit'];
        $withCount = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('customer_name', $request->search), WhereClause::queryLike('customer_phone', $request->search)]));
        }

        if ($request->has('created_date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->created_date));
        }

        if ($request->has('code')) {
            array_push($clauses, WhereClause::queryLike('code', $request->code));
        }

        if ($request->has('order_status')) {
            array_push($clauses, WhereClause::query('order_status', $request->order_status));
        }

        if ($request->has('ship_status')) {
            $status = $request->ship_status;
            array_push($clauses, WhereClause::queryRelationHas('shipping', function ($q) use ($status) {
                $q->where('status', $status);
            }));
        }

        if ($limit) {
            $data = $this->orderRepository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->orderRepository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $successOrders = [];

        $validator = Validator::make($request->all(), [
            'order_ids' => 'required',
            'unit_id' => 'required|numeric',
            'store_id' => 'required|numeric',
            'service_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $orderIds = preg_split('/,/', $request->order_ids);

        $orders = $this->orderRepository->get([WhereClause::queryIn('id', $orderIds)], 'date_created:desc', ['details.warehouse', 'shipping.unit']);
        $unit = $this->unitRepository->findById($request->unit_id);

        if (empty($unit)) {
            return $this->errorClient('Không hỗ trợ đơn vị này');
        }

        $store = $this->storeRepository->findById($request->store_id);
        if (empty($store)) {
            return $this->errorClient('Không tìm thấy cửa hàng');
        }

        $service = $this->serviceRepository->findById($request->service_id);
        if (empty($service)) {
            return $this->errorClient('Không tìm thấy dịch vụ');
        }

        foreach ($orders as $order) {
            foreach ($order->details as $d) {
                if ($d->warehouse->weight <= 0 && $unit->name != UnitName::TU_GIAO) {
                    return $this->errorClient('Sản phẩm ' . $d->product_code . ' chưa cấu hình khối lượng');
                }
                if ($d->warehouse->quantity < $d->quantity) {
                    return $this->errorClient('Sản phẩm ' . $d->product_code . ' không đủ hàng trong kho');
                }
            }
        }

        foreach ($orders as $order) {
            if (!($order instanceof Order)) {
                continue;
            }
            if ($order->shipping) {
                continue;
            }
            try {
                DB::beginTransaction();
                $giaoHangUtil = new GiaoHangUtil($unit);
                $dataResponse = $giaoHangUtil->createOrder($order, $store, $service);
                $attributes['order_id'] = $order->id;
                $attributes['unit_id'] = $unit->id;
                $attributes['service_id'] = $service->id;
                $attributes['store_id'] = $store->id;
                $attributes['code'] = $dataResponse->order_code;
                $attributes['total_fee'] = $dataResponse->total_fee;
                $attributes['expected_delivery_time'] = $dataResponse->expected_delivery_time;
                if ($unit->name == UnitName::TU_GIAO) {
                    $attributes['status'] = 'Điều phối giao hàng';
                } else {
                    $attributes['status'] = 'Đã tiếp nhận';
                }
                $attributes['status_id'] = 2;
                $attributes['note'] = null;
                $shipping = $this->repository->create($attributes);
                if($shipping) {
                    $this->orderRepository->update($order->id, [
                        'order_status' => Order::$DA_CHUAN_BI_HANG
                    ]);
                    foreach ($order->details as $d) {
                        $warehouse = $this->warehouseRepository->update($d->warehouse_id, [
                            'quantity' => $d->warehouse->quantity - $d->quantity,
                            'use_quantity' => $d->warehouse->use_quantity + $d->quantity
                        ]);
                        if($warehouse->quantity == 0) {
                            $this->warehouseRepository->update($warehouse->id, [
                                'status' => 0
                            ]);
                        }
                    }
                }
                DB::commit();
                $order->load(['shipping.unit']);
                array_push($successOrders, $order);
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
            }
        }
        return $this->success($successOrders);

    }

    public function show($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Đơn hàng không tồn tạ 21i');
        }

        $ghu = new GiaoHangUtil($model);
        $info = $ghu->getOrder($model);
        return $this->success($info);
    }

    public function shipping($id)
    {
        $model = $this->repository->findById($id ,['order']);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, [
                'status' => 'Đang giao',
                'status_id' => 4,
            ]);
            if($model) {
                $this->orderRepository->update($model->order->id,
                [
                    'order_status' => 'Đang giao'
                ]);
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function complete($id)
    {
        $model = $this->repository->findById($id ,['order']);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, [
                'status' => 'Hoàn thành',
                'status_id' => 7,
            ]);
            if($model) {
                $this->orderRepository->update($model->order->id,
                [
                    'order_status' => 'Hoàn thành',
                    'is_completed' => 1
                ]);
            }
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function printBills(Request $request)
    {
        $orderIdsStr = $request->order_ids;
        if (empty($orderIdsStr)) {
            return $this->error('Không có đơn hàng nào');
        }
        $orderIds = preg_split('/,/', $orderIdsStr);
        $models = $this->orderRepository->get([WhereClause::queryIn('id',$orderIds)], null, ['shipping']);
        if (empty($models)) {
            return $this->error('Đối tượng không tồn tại');
        }

        try {
            DB::beginTransaction();
            $ghu = new GiaoHangUtil($models[0]);
            $link = $ghu->printOrders($models);
            $this->repository->bulkUpdate([WhereClause::queryIn('id',$orderIds)],['is_printed' => 1]);
            DB::commit();
            return $this->success(compact('link'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $models);
        }
    }

    public function printBill($id)
    {
        $model = $this->repository->findById($id, ['order']);
        if (empty($model)) {
            return $this->error('Đơn hàng không tồn tại');
        }

        try {
            $ghu = new GiaoHangUtil($model);
            $this->repository->update($id,['is_printed' => 1]);
            return $this->success(['link' => $ghu->printOrders([$model->order])]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function note($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $attributes['note'] = $request->note;
        $attributes['status'] = OrderShip::$GIAO_LAI;
        $attributes['status_id'] = 5;

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            $this->orderRepository->update($model->order_id, ['order_status' => Order::$DANG_GIAO]);
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
