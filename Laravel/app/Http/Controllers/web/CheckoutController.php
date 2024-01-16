<?php

namespace App\Http\Controllers\web;

use App\Common\Enum\OrderPaymentStatusEnum;
use App\Common\Enum\PaymentMethodEnum;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Order;
use App\Repository\CartItemRepositoryInterface;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\NotificationRepositoryInterface;
use App\Repository\OrderDetailRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaymentMethodRepositoryInterface;
use App\Repository\PaymentTransactionRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Utils\Payments\MomoUtil;
use App\Utils\Payments\VnPayUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends RestController
{
    protected $detailRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;
    protected $itemRepository;
    protected $profileRepository;
    protected $voucherRepository;
    protected $notificationRepository;
    protected $shipFeeRepository;
    protected $promotionRepository;
    protected $paymentMethodRepository;
    protected $transactionRepository;

    public function __construct(
        OrderRepositoryInterface         $repository,
        OrderDetailRepositoryInterface   $detailRepository,
        ProvinceRepositoryInterface      $provinceRepository,
        DistrictRepositoryInterface      $districtRepository,
        WardRepositoryInterface          $wardRepository,
        CartItemRepositoryInterface      $itemRepository,
        UserProfileRepositoryInterface   $profileRepository,
        VoucherRepositoryInterface       $voucherRepository,
        NotificationRepositoryInterface  $notificationRepository,
        ShippingFeeRepositoryInterface   $shipFeeRepository,
        PromotionRepositoryInterface     $promotionRepository,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        PaymentTransactionRepositoryInterface $transactionRepository
    ) {
        parent::__construct($repository);
        $this->detailRepository = $detailRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->itemRepository = $itemRepository;
        $this->profileRepository = $profileRepository;
        $this->voucherRepository = $voucherRepository;
        $this->notificationRepository = $notificationRepository;
        $this->shipFeeRepository = $shipFeeRepository;
        $this->promotionRepository = $promotionRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Request $request)
    {
        $provinceUser = null;
        $districtUser = null;
        $wardUser = null;
        $shipFee = 0;
        $discount = 0;
        $total = 0;
        $itemC = $request->item;
        $items = explode(",", $itemC);
        $clauses = [WhereClause::queryIn('id', $items)];
        $with = ['product', 'warehouse.size', 'warehouse.color'];
        $provinces = $this->provinceRepository->get([]);
        $profile = $this->profileRepository->find([WhereClause::query('user_id', Auth::user()->id)]);
        $itemCheckout = $this->itemRepository->get($clauses, null, ['product']);

        if(!$request->has('item') || count($itemCheckout) <= 0) {
            return redirect('/cart');
        }

        if ($profile->province != null) {
            $provinceUser = $this->provinceRepository->find([WhereClause::query('name', $profile->province)], null, ['districts']);
        }

        if ($profile->district != null) {
            $districtUser = $this->districtRepository->find([WhereClause::query('name', $profile->district)], null, ['wards']);
        }

        if ($profile->ward != null) {
            $wardUser = $this->wardRepository->find([WhereClause::query('name', $profile->ward)]);
            if ($wardUser) {
                $shipFee = $this->shipFeeRepository->find([WhereClause::query('ward_id', $wardUser->id)])->fee;
            }
        }

        $payment = $this->paymentMethodRepository->get([WhereClause::query('status', 1)],'id:asc');

        foreach ($itemCheckout as $item) {
            $total += $item->quantity * $item->product->sale_price;
        }

        $promotion = $this->promotionRepository->find(
            [
                WhereClause::queryIn('type', ['3', '4']),
                WhereClause::query('status', 1),
                WhereClause::query('expired_date', date('Y-m-d'), '>'),
                WhereClause::query('min_order_value', $total, '<')
            ],
            'type:desc,min_order_value:desc'
        );
        if (!empty($promotion)) {
            if ($promotion->type == 4) {
                $discount = ($total * $promotion->discount_percent / 100) + $promotion->discount_value;
            } else {
                $shipFee = 0;
            }
        }

        $totalAll = $total + $shipFee - $discount;

        $dataItem = [
            'total' => $total,
            'shipFee' => $shipFee,
            'discount' => $discount,
            'totalAll' => $totalAll
        ];
        return view('orders.checkout', compact('itemCheckout', 'provinces', 'itemC', 'profile', 'provinceUser', 'districtUser', 'wardUser', 'promotion', 'dataItem', 'payment'));
    }

    public function store(Request $request)
    {
        $items = explode(",", $request->items);
        $clauses = [WhereClause::queryIn('id', $items)];
        $with = ['product', 'warehouse.size', 'warehouse.color'];
        $province = $this->provinceRepository->findById($request->province_id);
        $district = $this->districtRepository->findById($request->district_id);
        $ward = $this->wardRepository->findById($request->ward_id);
        $attributes = $request->only([
            'customer_name',
            'customer_phone',
            'customer_address',
            'customer_request',
            'amount',
            'shipping_fee',
            'total_amount',
            'discount',
            'payment_type'
        ]);

        $code = 'DH' . Str::random(8);
        while (Order::query()->where('code', $code)->exists()) {
            $code = 'DH' . Str::random(8);
        }

        $attributes['code'] = $code;
        $attributes['user_id'] = Auth::user()->id;
        $attributes['province'] = $province->name;
        $attributes['district'] = $district->name;
        $attributes['ward'] = $ward->name;
        $attributes['order_status'] = Order::$LEN_DON;
        $attributes['payment_status'] = 0;
        $attributes['date_created'] = date('Y-m-d');
        $attributes['voucher_id'] = $request->voucherId;
        $attributes['cod_fee'] = $request->total_amount;

        try {
            DB::beginTransaction();
            $order = $this->repository->create($attributes);
            $itemCheckouts = $this->itemRepository->get($clauses, null, $with);

            foreach ($itemCheckouts as $item) {
                $attributeDetails['order_id'] = $order->id;
                $attributeDetails['product_id'] = $item->product->id;
                $attributeDetails['product_code'] = $item->product->code;
                $attributeDetails['product_name'] = $item->product->name;
                $attributeDetails['warehouse_id'] = $item->warehouse_id;
                $attributeDetails['size'] = $item->warehouse->size->name;
                $attributeDetails['color'] = $item->warehouse->color->name;
                $attributeDetails['unit_price'] = $item->product->sale_price;
                $attributeDetails['quantity'] = $item->quantity;
                $attributeDetails['amount'] = $item->product->sale_price * $item->quantity;
                $detail = $this->detailRepository->create($attributeDetails);
                if ($detail) {
                    $this->itemRepository->delete($item->id);
                }
            }

            if ($order) {
                $paymentProcess = null;
                $paymentMethod = null;

                if ($request->payment_type == PaymentMethodEnum::VNPAY) {
                    $paymentMethod = $this->paymentMethodRepository->find([WhereClause::query('name', PaymentMethodEnum::VNPAY)]);
                    $paymentProcess = VnPayUtil::getInstance()->createRequest($order, $paymentMethod, $items);
                }

                if ($request->payment_type == PaymentMethodEnum::MOMO) {
                    $paymentMethod = $this->paymentMethodRepository->find([WhereClause::query('name', PaymentMethodEnum::MOMO)]);
                    $paymentProcess = MomoUtil::getInstance()->createRequest($order, $paymentMethod, $items);
                }

                if ($attributes['voucher_id'] != null) {
                    $this->voucherRepository->update($attributes['voucher_id'],['remain_quantity' => DB::raw('`remain_quantity` - 1')]);
                }

                $this->notificationRepository->create(
                    [
                        'name' => 'Đơn hàng ' . $order->code . ' được tạo thành công',
                        'content' => 'Bạn đã tạo đơn hàng thành công. Chúng tôi sẽ sớm gửi đơn hàng này đến bạn. Cảm ơn bạn đã ủng hộ cửa hàng.',
                        'slug' => 'don-hang-' . $order->code,
                        'user_id' => Auth::user()->id,
                        'type' => 2
                    ]
                );
            }
            DB::commit();
            return view('orders.payment', compact('order', 'paymentProcess', 'paymentMethod'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Thêm đơn hàng thất bại');
        }
    }

    public function vnpay(Request $request)
    {
        $inputData = $request->all();
        $orderCode = $inputData['vnp_TxnRef'];
        $order = $this->repository->find([WhereClause::query('code', $orderCode)]);

        $payment = VnPayUtil::getInstance()->updateIPN($inputData, $order);

        if ($payment['status'] == OrderPaymentStatusEnum::COMPLETED) {
            try {
                DB::beginTransaction();
                $this->repository->update($order, [
                    'cod_fee' => 0,
                    'payment_status' => 1,
                ]);
                $this->transactionRepository->create([
                    'name' => 'Giao dịch thanh toán đơn hàng '. $order->code,
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'creator_name' => $order->customer_name,
                    'amount' => $order->total_amount,
                    'method' => $order->payment_type,
                    'status' => $payment['status'],
                    'message' => $payment['message'],
                    'dump_data' => json_encode($inputData),
                    'type' => 0
                ]);
                DB::commit();
                return $this->successView('cart', 'Đã thanh toán đơn hàng thành công');
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return redirect('cart')->with('Thanh toán đơn hàng thất bại');
            }
        } else {
            return $this->successView('cart', 'Thêm đơn hàng thành công');
        }
    }

    public function momo(Request $request)
    {
        $inputData = $request->all();
        $orderCode = $inputData['orderId'];
        $order = $this->repository->find([WhereClause::query('code', $orderCode)]);

        $payment = MomoUtil::getInstance()->updateIPN($inputData, $order);
        if ($payment['status'] == OrderPaymentStatusEnum::COMPLETED) {
            try {
                DB::beginTransaction();
                $this->repository->update($order, [
                    'cod_fee' => 0,
                    'payment_status' => 1,
                ]);
                $this->transactionRepository->create([
                    'name' => 'Giao dịch thanh toán đơn hàng '. $order->code,
                    'order_id' => $order->id,
                    'order_code' => $order->code,
                    'creator_name' => $order->customer_name,
                    'amount' => $order->total_amount,
                    'method' => $order->payment_type,
                    'status' => $payment['status'],
                    'message' => $payment['message'],
                    'dump_data' => json_encode($inputData),
                    'type' => 0
                ]);
                DB::commit();
                return $this->successView('cart', 'Đã thanh toán đơn hàng thành công');
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
                return redirect('cart')->with('Thanh toán đơn hàng thất bại');
            }
        } else {
            return $this->successView('cart', 'Thêm đơn hàng thành công');
        }
    }

}
