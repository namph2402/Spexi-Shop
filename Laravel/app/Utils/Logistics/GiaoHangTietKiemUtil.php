<?php

namespace App\Utils\Logistics;

use App\Models\Order;
use App\Models\OrderShip;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\StoreInformation;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;

class GiaoHangTietKiemUtil extends GiaoHangAbstractUtil
{
    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Giao hàng Tiết kiệm';
        }
        parent::__construct($input);
    }

    public static function getSerialFromCode($code)
    {
        $segments = array_reverse(preg_split('/\./', $code));
        return $segments[0];
    }

    public static function getLocationFromCode($code)
    {
        $segments = array_reverse(preg_split('/\./', $code));
        return $segments[1];
    }

    public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null)
    {
        $data = [
            'products' => [
            ],
            'order' => [
                'id' => $order->id . '_' . (string)now()->timestamp,
                "pick_name" => $store->data['pick_name'],
                "pick_address" => $store->data['pick_address'],
                "pick_province" => $store->data['pick_province'],
                "pick_district" => $store->data['pick_district'],
                "pick_ward" => $store->data['pick_ward'],
                "pick_tel" => $store->data['pick_tel'],
                'name' => $order->customer_name,
                'address' => $order->customer_address,
                'province' => $order->province,
                'district' => $order->district,
                'ward' => $order->ward,
                'hamlet' => 'Khác',
                'tel' => $order->customer_phone,
                'note' => $service->data['default_note'] ?? '',
                'email' => '',
                'is_freeship' => '1',
                'pick_money' => $order->cod_fee,
                'value' => $order->total_amount,
                'pick_option' => $service->data['pick_option'] ?? 'cod',
                'actual_transfer_method' => $service->data['actual_transfer_method'] ?? 'road',
                'transport' => $service->data['transport'] ?? 'road',
            ]
        ];

        if (isset($store->data['total_weight'])) {
            $data['order']['total_weight'] = $store->data['total_weight'];
        }

        if (isset($service->data['deliver_option'])) {
            $data['order']['deliver_option'] = $service->data['deliver_option'];
        }

        foreach ($order->details as $d) {
            $variant = Warehouse::whereProductId($d->product_id)->whereId($d->variant_id)->first();
            array_push($data['products'], [
                'name' => $d->detail_code,
                'price' => $d->unit_price,
                'weight' => $variant->weight,
                'quantity' => $d->quantity,
                'product_code' => $d->product_code
            ]);
        }

        $curl = Curl::to($this->endpoint . '/services/shipment/order/?ver=1.6.3')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'token' => $this->token,
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->success == 1) {
                $responseData = $curl->content->order;
                $data = new \stdClass();
                $data->order_code = $responseData->label;
                $data->total_fee = $responseData->fee;
                $d_time = $responseData->estimated_deliver_time;
                if (Str::startsWith($d_time, 'Sáng ')) {
                    $d_time = str_replace('Sáng ', '', $d_time) . ' 08:00:00';
                } else {
                    if (Str::startsWith($d_time, 'Chiều ')) {
                        $d_time = str_replace('Chiều ', '', $d_time) . ' 14:00:00';
                    } else {
                        $d_time = now()->addDays(3)->toDateTimeString();
                    }
                }
                $data->expected_delivery_time = $d_time;
                return $data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception($curl->content);
        }
    }

    public function cancelOrder(OrderShip $order)
    {
        $curl = Curl::to($this->endpoint . '/services/shipment/cancel/' . $order->code)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'token' => $this->token,
            ])
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->success == 1 || $curl->content->message == 'Đơn hàng đã ở trạng thái hủy') {
                return true;
            }
        }
        return false;
    }

    public function authenticate($account)
    {
        // TODO: Implement authenticate() method.
    }

    public function getServices()
    {
        $services = [];

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Chuẩn + Đến lấy hàng + Đường bộ',
            'code' => 'cod_standard',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'cod',
                'deliver_option' => '',
                'actual_transfer_method' => 'road',
                'transport' => 'road'
            ],
            'is_often' => 1
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Chuẩn + Giao tại cửa hàng + Đường bộ',
            'code' => 'post_standard',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'post',
                'deliver_option' => '',
                'actual_transfer_method' => 'road',
                'transport' => 'road'
            ],
            'is_often' => 0
        ]);
        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Chuẩn + Đến lấy hàng + Đường bay',
            'code' => 'cod_standard',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'cod',
                'deliver_option' => '',
                'actual_transfer_method' => 'fly',
                'transport' => 'fly'
            ],
            'is_often' => 1
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Chuẩn + Giao tại cửa hàng + Đường bay',
            'code' => 'post_standard',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'post',
                'deliver_option' => '',
                'actual_transfer_method' => 'fly',
                'transport' => 'fly'
            ],
            'is_often' => 0
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'XTeam + Đến lấy hàng + Đường bộ',
            'code' => 'cod_xteam',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'cod',
                'deliver_option' => 'xteam',
                'actual_transfer_method' => 'road',
                'transport' => 'road'
            ],
            'is_often' => 0
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'XTeam + Giao tại cửa hàng  + Đường bộ',
            'code' => 'post_xteam',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'post',
                'deliver_option' => 'xteam',
                'actual_transfer_method' => 'road',
                'transport' => 'road'
            ],
            'is_often' => 0
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'XTeam + Đến lấy hàng + Đường bay',
            'code' => 'cod_xteam',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'cod',
                'deliver_option' => 'xteam',
                'actual_transfer_method' => 'fly',
                'transport' => 'fly'
            ],
            'is_often' => 0
        ]);

        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'XTeam + Giao tại cửa hàng  + Đường bay',
            'code' => 'post_xteam',
            'data' => [
                'default_note' => 'Khách không lấy hàng thu ship 30k',
                'pick_option' => 'post',
                'deliver_option' => 'xteam',
                'actual_transfer_method' => 'fly',
                'transport' => 'fly'
            ],
            'is_often' => 0
        ]);

        return $services;

    }

    public function getStores()
    {
        $curl = Curl::to($this->endpoint . '/services/shipment/list_pick_add')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'token' => $this->token,
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->success == true) {
                $respData = $curl->content->data;
                $shops = [];
                foreach ($respData as $index => $rd) {
                    $data = (array)$rd;
                    $addresses = array_reverse(preg_split('/,\s/', $rd->address));
                    $data['pick_province'] = $addresses[0];
                    $data['pick_district'] = $addresses[1];
                    $data['pick_ward'] = $addresses[2];
                    $data['pick_address'] = $addresses[3];
                    $isOften = $index == 0 ? 1 : 0;
                    array_push($shops, [
                        'unit_id' => $this->getShippingUnit()->id,
                        'name' => $rd->pick_name,
                        'partner_id' => $rd->pick_address_id,
                        'data' => $data,
                        'is_often' => $isOften
                    ]);
                }

                return $shops;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function getProvinces()
    {
        // TODO: Implement getProvinces() method.
    }

    public function getDistricts($provinceId)
    {
        // TODO: Implement getDistricts() method.
    }

    public function getWards($districtId)
    {
        // TODO: Implement getWards() method.
    }

    public function getOrder(OrderShip $order)
    {
        $curl = Curl::to($this->endpoint . '/services/shipment/v2/' . $order->code)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'token' => $this->token,
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->success == 1) {
                return $curl->content->order;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function updateOrder(string $code, Order $order, ShippingService $service = null)
    {
        // TODO: Implement updateOrder() method.
    }

    public function returnOrder(OrderShip $order)
    {
        // TODO: Implement returnOrder() method.
    }

    public function storingOrder(OrderShip $order)
    {
        // TODO: Implement storingOrder() method.
    }

    public function printOrders($orders)
    {
        $dataStoreOrder = [
            'logo' => StoreInformation::whereName('Logo')->first()->value,
            'name' => StoreInformation::whereName('Name')->first()->value,
            'address' => StoreInformation::whereName('Address')->first()->value,
            'phone' => StoreInformation::whereName('Phone')->first()->value,
            'barcodeType' => 'CODE128',
        ];
        return view('giaohang.default', compact('orders', 'dataStoreOrder'))->render();
    }
}
