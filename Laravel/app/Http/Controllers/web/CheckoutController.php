<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Order;
use App\Repository\CartItemRepositoryInterface;
use App\Repository\DistrictRepositoryInterface;
use App\Repository\NotificationRepositoryInterface;
use App\Repository\OrderDetailRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\ShippingFeeRepositoryInterface;
use App\Repository\UserProfileRepositoryInterface;
use App\Repository\WardRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
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

    public function __construct(
        OrderRepositoryInterface        $repository,
        OrderDetailRepositoryInterface  $detailRepository,
        ProvinceRepositoryInterface     $provinceRepository,
        DistrictRepositoryInterface     $districtRepository,
        WardRepositoryInterface         $wardRepository,
        CartItemRepositoryInterface     $itemRepository,
        UserProfileRepositoryInterface  $profileRepository,
        VoucherRepositoryInterface      $voucherRepository,
        NotificationRepositoryInterface $notificationRepository,
        ShippingFeeRepositoryInterface  $shipFeeRepository,
        PromotionRepositoryInterface    $promotionRepository
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
        $with = ['product', 'warehouse.sizes', 'warehouse.colors'];

        $provinces = $this->provinceRepository->get([]);
        $itemCheckout = $this->itemRepository->get($clauses, null, $with);
        $profile = $this->profileRepository->find([WhereClause::query('user_id', Auth::user()->id)]);

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

        foreach ($itemCheckout as $item) {
            $total += $item->amount;
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
        return view('orders.checkout', compact('itemCheckout', 'provinces', 'itemC', 'profile', 'provinceUser', 'districtUser', 'wardUser', 'promotion', 'dataItem'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'customer_name' => 'required|max:255',
            'customer_phone' => 'required|numeric',
            'customer_address' => 'required|max:255',
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'ward_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'shipping_fee' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'items' => 'required',
        ]);
        if ($validator) {
            return $this->errorClient($validator);
        }

        $items = explode(",", $request->items);
        $clauses = [WhereClause::queryIn('id', $items)];
        $with = ['product', 'warehouse.sizes', 'warehouse.colors'];

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
            'payment_type',
            'discount'
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

        if ($request->payment_type == 'manual') {
            $attributes['cod_fee'] = $request->total_amount;
        } else {
            $attributes['cod_fee'] = 0;
        }

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
                $attributeDetails['size'] = $item->warehouse->sizes->name;
                $attributeDetails['color'] = $item->warehouse->colors->name;
                $attributeDetails['unit_price'] = $item->product->sale_price;
                $attributeDetails['quantity'] = $item->quantity;
                $attributeDetails['amount'] = $item->amount;
                $detail = $this->detailRepository->create($attributeDetails);
                if ($detail) {
                    $this->itemRepository->delete($item->id);
                }
            }
            if ($order) {
                if ($attributes['voucher_id'] != null) {
                    $this->voucherRepository->update(
                        $attributes['voucher_id'],
                        [
                            'remain_quantity' => DB::raw('`remain_quantity` - 1')
                        ]
                    );
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
            return $this->successView('cart', 'Thêm đơn hàng thành công');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->errorView('Thêm đơn hàng thất bại');
        }
    }
}
