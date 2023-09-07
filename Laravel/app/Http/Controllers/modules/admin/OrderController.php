<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\ChangeStatusOrder;
use App\Models\Order;
use App\Repository\OrderDetailRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\WarehouseRepositoryInterface;
use App\Utils\GiaoHangUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends RestController
{
    protected $productRepository;
    protected $detailRepository;
    protected $warehouseRepository;
    protected $userRepository;
    protected $voucherRepository;

    public function __construct(
        OrderRepositoryInterface       $repository,
        ProductRepositoryInterface     $productRepository,
        OrderDetailRepositoryInterface $detailRepository,
        VoucherRepositoryInterface     $voucherRepository,
        UserProfileRepositoryInterface $userRepository,
        WarehouseRepositoryInterface   $warehouseRepository
    ) {
        parent::__construct($repository);
        $this->productRepository = $productRepository;
        $this->detailRepository = $detailRepository;
        $this->voucherRepository = $voucherRepository;
        $this->userRepository = $userRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $orderBy = $request->input('orderBy', 'updated_at:desc');
        $with = ['details.product.warehouseViews.size', 'details.product.warehouseViews.color', 'voucher'];
        $withCount = [];

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('customer_name', $request->search), WhereClause::queryLike('customer_phone', $request->search)]));
        }

        if ($request->has('code') && Str::length($request->code) > 0) {
            array_push($clauses, WhereClause::query('code', $request->code));
        }

        if ($request->has('created_date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->created_date));
        }

        if ($request->has('order_status')) {
            array_push($clauses, WhereClause::query('order_status', $request->order_status));
        }

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
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'product' => 'required',
            'shipping_fee' => 'required|numeric',
            'amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'payment_type' => 'required'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $code = 'DH' . Str::random(8);
        while (Order::query()->where('code', $code)->exists()) {
            $code = 'DH' . Str::random(8);
        }

        $attributes = $request->only([
            'customer_name',
            'customer_phone',
            'customer_address',
            'customer_request',
            'province',
            'district',
            'ward',
            'payment_type',
            'amount',
            'discount',
            'shipping_fee',
            'total_amount',
            'voucher_id',
            'payment_status',
        ]);

        $customer = $this->userRepository->find([WhereClause::query('phone', $request->customer_phone)]);
        if ($customer == null) {
            $attributes['user_id'] = 0;
        } else {
            $attributes['user_id'] = $customer->user_id;
        }

        $attributes['code'] = $code;
        $attributes['date_created'] = date('Y-m-d');
        $attributes['order_status'] = Order::$LEN_DON;
        $attributes['cod_fee'] = $request->total_amount;

        $products = json_decode($request->product, true);
        if(empty($products)) {
            return $this->errorClient("Chưa chọn sản phẩm nào");
        }

        try {
            DB::beginTransaction();
            $order = $this->repository->create($attributes);
            foreach ($products as $p) {
                $warehouse = $this->warehouseRepository->findById($p['warehouse_id'], ['size', 'color']);
                $attributeDetails['order_id'] = $order->id;
                $attributeDetails['product_id'] = $p['product']['id'];
                $attributeDetails['product_code'] = $p['product']['code'];
                $attributeDetails['product_name'] = $p['product']['name'];
                $attributeDetails['warehouse_id'] = $p['warehouse_id'];
                $attributeDetails['size'] = $warehouse->size->name;
                $attributeDetails['color'] = $warehouse->color->name;
                $attributeDetails['quantity'] = $p['quantity'];
                $attributeDetails['unit_price'] = $p['unit_price'];
                $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];
                $this->detailRepository->create($attributeDetails);
            }

            if ($order && $request->has('voucher_id')) {
                $voucher = $this->voucherRepository->findById($request->voucher_id);
                $this->voucherRepository->update($voucher->id, ['remain_quantity' => $voucher->remain_quantity - 1]);
            }
            DB::commit();
            return $this->success($order);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $order = $this->repository->findById($id, ['details','shipping']);
        if (empty($order)) {
            return $this->errorClient('Đơn hàng không tồn tại');
        }

        if ($order->shipping) {
            return $this->errorClient('Không thể sửa đơn hàng');
        }

        $validator = $this->validateRequest($request, [
            'customer_name' => 'nullable',
            'customer_phone' => 'nullable',
            'customer_address' => 'nullable',
            'province' => 'nullable',
            'district' => 'nullable',
            'ward' => 'nullable',
            'product' => 'nullable',
            'shipping_fee' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'payment_type' => 'nullable'
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $attributes = $request->only([
            'customer_name',
            'customer_phone',
            'customer_address',
            'customer_request',
            'province',
            'district',
            'ward',
            'payment_type',
            'amount',
            'discount',
            'shipping_fee',
            'total_amount',
            'voucher_id',
            'payment_status',
        ]);

        if ($request->payment_status == 0) {
            $attributes['cod_fee'] = $request->total_amount;
        } else {
            $attributes['cod_fee'] = 0;
        }

        $attributes['order_status'] = Order::$LEN_DON;

        $products = json_decode($request->product, true);
        if(empty($products)) {
            return $this->errorClient("Chưa chọn sản phẩm nào");
        }

        try {
            DB::beginTransaction();
            $variant = [];
            foreach ($products as $p) {
                array_push($variant, $p['warehouse_id']);
            }
            foreach ($order->details as $d) {
                $check = in_array($d->warehouse_id, $variant);
                if (!$check) {
                    $this->detailRepository->delete($d->id);
                }
            }

            foreach ($products as $p) {
                $warehouse = $this->warehouseRepository->findById($p['warehouse_id'], ['size', 'color']);

                $order_detail = $this->detailRepository->find([
                    WhereClause::query('order_id', $order->id),
                    WhereClause::query('product_id', $p['product']['id']),
                    WhereClause::query('warehouse_id', $p['warehouse_id'])
                ]);

                $attributeDetails['product_id'] = $p['product']['id'];
                $attributeDetails['product_code'] = $p['product']['code'];
                $attributeDetails['product_name'] = $p['product']['name'];
                $attributeDetails['warehouse_id'] = $p['warehouse_id'];
                $attributeDetails['size'] = $warehouse->size->name;
                $attributeDetails['color'] = $warehouse->color->name;
                $attributeDetails['quantity'] = $p['quantity'];
                $attributeDetails['unit_price'] = $p['unit_price'];
                $attributeDetails['amount'] = $attributeDetails['quantity'] * $attributeDetails['unit_price'];

                if (empty($order_detail)) {
                    $attributeDetails['order_id'] = $order->id;
                    $this->detailRepository->create($attributeDetails);
                } else {
                    $this->detailRepository->update($order_detail->id, $attributeDetails);
                }
            }

            if ($request->has('voucher_id')) {
                if ($request->voucher_id == null) {
                    if ($order->voucher_id != null) {
                        $voucher = $this->voucherRepository->findById($order->voucher_id);
                        $this->voucherRepository->update($voucher->id, ['remain_quantity' => $voucher->remain_quantity + 1]);
                    }
                    $attributes['voucher_id'] = null;
                } else {
                    if ($order->voucher_id != null) {
                        if ($request->voucher_id != $order->voucher_id) {
                            $voucherOld = $this->voucherRepository->findById($order->voucher_id);
                            $voucherNew = $this->voucherRepository->findById($request->voucher_id);
                            $this->voucherRepository->update($voucherOld->id, ['remain_quantity' => $voucherOld->remain_quantity + 1]);
                            $this->voucherRepository->update($request->voucher_id, ['remain_quantity' => $voucherNew->remain_quantity - 1]);
                            $attributes['voucher_id'] = $request->voucher_id;
                        }
                    } else {
                        $voucher = $this->voucherRepository->findById($request->voucher_id);
                        $this->voucherRepository->update($request->voucher_id, ['remain_quantity' => $voucher->remain_quantity - 1]);
                        $attributes['voucher_id'] = $request->voucher_id;
                    }
                }
            }
            $order = $this->repository->update($id, $attributes);
            DB::commit();
            return $this->success($order);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            return $this->error($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorClient('Đối tượng không tồn tại');
        }

        if ($model->status == Order::$LEN_DON) {
            $model->delete();
            return $this->success($model);
        } else {
            return $this->errorClient('Đơn hàng này không thể xóa');
        }
    }

    public function confirm($id, Request $request)
    {
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $attributes['note'] = $request->note;
        $attributes['order_status'] = Order::$XAC_NHAN;

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            DB::commit();
            ChangeStatusOrder::dispatch($model->id);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function cancel($id, Request $request)
    {
        $model = $this->repository->findById($id,['shipping']);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $attributes['note'] = $request->note;
        $attributes['order_status'] = Order::$HUY_DON;

        try {
            DB::beginTransaction();
            $model = $this->repository->update($id, $attributes);
            if ($model->shipping) {
                $ghUtil = new GiaoHangUtil($model);
                $response = $ghUtil->cancelOrder($model->shipping);
                if ($response) {
                    $model->shipping->save();
                } else {
                    return $this->errorClient('Không thể hủy đơn hàng');
                }
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
        $model = $this->repository->findById($id);
        if (empty($model)) {
            return $this->errorNotFound();
        }

        $attributes['order_status'] = Order::$HOAN_THANH;

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

}
