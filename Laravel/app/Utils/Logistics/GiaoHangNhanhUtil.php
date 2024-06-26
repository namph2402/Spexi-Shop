<?php

namespace App\Utils\Logistics;

use App\Models\Order;
use App\Models\OrderShip;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;

class GiaoHangNhanhUtil extends GiaoHangAbstractUtil
{
    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Giao Hàng Nhanh';
        }
        parent::__construct($input);
    }

    public function getOrder(OrderShip $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/shipping-order/detail')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token
            ])
            ->withData([
                'order_code' => $order->code
            ])
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null)
    {
        $data = [
            'from_name' => $store->name,
            'to_name' => $order->customer_name,
            'to_phone' => $order->customer_phone,
            'to_address' => $order->customer_address,
            'to_ward_name' => $order->ward,
            'to_district_name' => $order->district,
            'to_province_name' => $order->province,
            'return_name' => $store->name,
            'return_phone' => $store->data['phone'],
            'return_address' => $store->data['address'],
            'return_ward_code' => $store->data['ward_code'],
            'return_district_id' => $store->data['district_id'],
            'client_order_code' => $order->code,
            'cod_amount' => $order->cod_fee,
            'service_id' => (int)$service->code,
            'service_type_id' => $service->data['service_type_id'],
            'payment_type_id' => (int)$service->data['payment_type_id'] ?? 1,
            'note' => $order->customer_request,
            'required_note' => $service->data['default_note'] ?? '',
            'length' => $service->data['default_length'] ?? 10,
            'width' => $service->data['default_width'] ?? 10,
            'height' => $service->data['default_height'] ?? 10,
            'items' => []
        ];

        $total_weight = 0;
        foreach ($order->details as $d) {
            $total_weight += $d->warehouse->weight * $d->quantity * 1000;
            array_push($data['items'], [
                'name' => $d->product_name,
                'code' => $d->product_code,
                'quantity' => $d->quantity,
            ]);
        }
        $data['weight'] = ceil($total_weight);

        $curl = Curl::to($this->endpoint . '/v2/shipping-order/create')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $store->partner_id
            ])
            ->withData($data)
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                $responseData = $curl->content->data;
                $data = new \stdClass();
                $data->order_code = $responseData->order_code;
                $data->total_fee = $responseData->total_fee;
                $data->expected_delivery_time = Carbon::parse($responseData->expected_delivery_time)->toDateTimeString();
                return $data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function cancelOrder(OrderShip $order)
    {
        $curl = Curl::to($this->endpoint . '/v2/switch-status/cancel')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'ShopId' => $order->store->partner_id
            ])
            ->withData(
                [
                    'order_codes' => [$order->code],
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->post();
        if ($curl->status == 200) {

            OrderShip::whereCode($order->code)->update(
                ['status' =>'Hủy đơn'],
                ['status_id' => 0]
            );

            if ($curl->content->code == 200) {
                return $curl->content->data[0]->result;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến Giao hàng nhanh');
        }
    }

    public function getServices()
    {
        $curl = json_decode('{"code": 200,"message": "Success","data":[
            {"service_id":53320,"short_name":"Chuyển phát thương mại điện tử","service_type_id":2},
            {"service_id":100039,"short_name":"Chuyển phát truyền thống","service_type_id":5}
            ]}', false);

        if ($curl->code == 200) {
            $services = [];
            $respData = $curl->data;

            foreach ($respData as $index => $d) {
                $data = (array)$d;
                $data['default_length'] = 10;
                $data['default_width'] = 10;
                $data['default_height'] = 10;
                $data['payment_type_id'] = 1;
                $data['default_note'] = 'KHONGCHOXEMHANG';
                $isOften = $index == 0 ? 1 : 0;
                array_push($services, [
                    'unit_id' => $this->getShippingUnit()->id,
                    'name' => $data['short_name'],
                    'code' => $data['service_id'],
                    'data' => $data,
                    'is_often' => $isOften
                ]);
            }
            return $services;
        } else {
            throw new \Exception($curl->content->message);
        }
    }

    public function getStores()
    {
        $curl = Curl::to($this->endpoint . '/v2/shop/all')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])
            ->withData(
                [
                    'offset' => 0,
                    'limit' => 200,
                    'clientphone' => ''
                ]
            )
            ->asJson()
            ->returnResponseObject()
            ->get();
        if ($curl->status == 200) {
            if ($curl->content->code == 200) {
                $respData = $curl->content->data->shops;
                $shops = [];
                foreach ($respData as $index => $rd) {
                    $data = (array)$rd;
                    $isOften = $index == 0 ? 1 : 0;
                    array_push($shops, [
                        'unit_id' => $this->getShippingUnit()->id,
                        'name' => $rd->name,
                        'partner_id' => $rd->_id,
                        'data' => $data,
                        'is_often' => $isOften
                    ]);
                }
                return $shops;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }
    }
}
