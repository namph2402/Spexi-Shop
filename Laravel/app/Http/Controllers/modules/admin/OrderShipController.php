<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\Enum\OrderStatus;
use App\Common\Enum\UnitName;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Order;
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
use Illuminate\Support\Str;

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
        $orderBy = $request->input('orderBy', 'date_created:asc');

        if ($request->has('search') && Str::length($request->search) > 0) {
            array_push($clauses, WhereClause::queryLike('customer_name', $request->search));
        }

        if ($request->has('search') && Str::length($request->search) == 0) {
            $data = '';
            return $this->success($data);
        }

        if ($request->has('customer_phone')) {
            array_push($clauses, WhereClause::queryLike('customer_phone', $request->customer_phone));
        }

        if ($request->has('created_date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->created_date));
        }

        if ($request->has('code')) {
            array_push($clauses, WhereClause::query('code', $request->code));
        }

        if ($request->has('status')) {
            array_push($clauses, WhereClause::query('order_status', $request->status));
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
            }
        }

        foreach ($orders as $order) {
            if (!($order instanceof Order)) {
                continue;
            }
            // if ($order->shipping) {
            //     continue;
            // }
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
                    $attributes['status'] = 'Đã điều phối giao hàng/Đang giao hàng';
                } else {
                    $attributes['status'] = 'Đã tiếp nhận';
                }
                $attributes['status_id'] = 2;
                $attributes['note'] = null;
                $shipping = $this->repository->create($attributes);

                // if($shipping) {
                //     $this->orderRepository->update($order->id, [
                //         'order_status' => Order::$DA_CHUAN_BI_HANG
                //     ]);
                // }

                DB::commit();
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
            return $this->errorClient('Đối tượng không tồn tại');
        }
        $ghu = new GiaoHangUtil($model);
        $info = $ghu->getOrder($model);
        return $this->success($info);
    }

    // public function destroy($id)
    // {
    //     $model = SaleOrderShipping::with('order')->find($id);
    //     if (empty($model)) {
    //         return $this->error('Đối tượng không tồn tại');
    //     }
    //     try {
    //         DB::beginTransaction();
    //         $ghu = new GiaoHangUtil($model);
    //         $ghu->cancelOrder($model);
    //         $model->delete();
    //         $model->order->status = 'Chuẩn bị hàng';
    //         $model->order->save();
    //         DB::commit();
    //         return $this->success($model);
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollBack();
    //         return $this->error($e->getMessage());
    //     }
    // }

    // public function recreate($id)
    // {
    //     $model = SaleOrderShipping::with(['order', 'store', 'unit', 'service'])->find($id);
    //     if (empty($model)) {
    //         return $this->error('Đối tượng không tồn tại');
    //     }
    //     try {
    //         DB::beginTransaction();
    //         $ghu = new GiaoHangUtil($model);
    //         $ghu->cancelOrder($model);
    //         $giaoHangUtil = new GiaoHangUtil($model->unit);
    //         $dataResponse = $giaoHangUtil->createOrder($model->order, $model->store, $model->service);
    //         $model->status = 'Đã tiếp nhận';
    //         $model->status_id = 2;
    //         $model->note = null;
    //         $model->code = $dataResponse->order_code;
    //         $model->total_fee = $dataResponse->total_fee;
    //         $model->expected_delivery_time = $dataResponse->expected_delivery_time;
    //         $model->save();
    //         $model->order->status = SaleOrder::$TRANG_THAI_DANG_GIAO;
    //         $model->order->save();
    //         DB::commit();
    //         $model->load(['unit']);
    //         return $this->success($model);
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         DB::rollBack();
    //         return $this->error($e->getMessage());
    //     }
    // }

    public function printBill($id)
    {
        $model = $this->repository->findById($id, ['order']);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        try {
            $ghu = new GiaoHangUtil($model);
            return $this->success(['link' => $ghu->printOrders([$model->order])]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
