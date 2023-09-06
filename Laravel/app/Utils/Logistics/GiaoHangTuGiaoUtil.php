<?php

namespace App\Utils\Logistics;


use App\Models\Order;
use App\Models\OrderShip;
use App\Models\ShippingService;
use App\Models\ShippingStore;

class GiaoHangTuGiaoUtil extends GiaoHangAbstractUtil
{
    public function __construct($input = null)
    {
        if ($input == null) {
            $input = 'Tự giao';
        }
        parent::__construct($input);
    }

    public function getServices()
    {
        $services = [];
        array_push($services, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Giao nhanh',
            'code' => '',
            'data' => [],
            'is_often' => 1
        ]);
        return $services;
    }

    public function getStores()
    {
        $shops = [];
        array_push($shops, [
            'unit_id' => $this->getShippingUnit()->id,
            'name' => 'Thời trang Spexi',
            'partner_id' => 1,
            'data' => [],
            'is_often' => 1
        ]);
        return $shops;
    }

    public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null)
    {
        $data = new \stdClass();
        $data->order_code = "TUGIAO_" . $order->id;
        $data->total_fee = 30000;
        $data->expected_delivery_time = now()->addDays(2)->toDateTimeString();
        return $data;
    }

    public function getOrder(OrderShip $order)
    {
        return $order;
    }

    public function cancelOrder(OrderShip $order)
    {
        $model = OrderShip::where('id', $order->id)->update(
            ['status' =>'Hủy đơn'],
            ['status_id' => 0]
        );
        return $model;
    }

}
