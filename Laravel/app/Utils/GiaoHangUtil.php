<?php

namespace App\Utils;

use App\Models\District;
use App\Models\Order;
use App\Models\OrderShip;
use App\Models\Province;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;
use App\Models\Ward;
use App\Utils\Logistics\GiaoHangAbstractUtil;
use Illuminate\Support\Facades\Storage;

class GiaoHangUtil
{
    /** @var GiaoHangAbstractUtil */
    protected $util;


    /**
     * @param ShippingUnit|OrderShip|Order|null $input
     */
    public function __construct($input = null)
    {
        if ($input instanceof OrderShip) {
            $input = $input->unit;
        }
        if ($input instanceof Order) {
            $shipping = $input->shipping;
            if (isset($shipping)) {
                $input = $shipping->unit;
            }
        }
        if (empty($input)) {
            $input = ShippingUnit::whereName("Tự giao")->first();
        }
        $this->util = new $input->class_name($input);
    }

    public static function checkAddress(Order $order)
    {
        $province = Province::where('name', $order->province)->first();
        if (empty($province)) {
            return false;
        }
        $district = District::where('name', $order->district)->first();
        if (empty($district)) {
            return false;
        }
        $ward = Ward::where('name', $order->ward)->first();
        if (empty($ward)) {
            return false;
        }
        return true;
    }

    public function getServices()
    {
        return $this->util->getServices();
    }

    public function getStores()
    {
        return $this->util->getStores();
    }

    public function getProvinces()
    {
        return $this->util->getProvinces();
    }

    public function getDistricts($provinceId)
    {
        return $this->util->getDistricts($provinceId);
    }

    public function getWards($districtId)
    {
        return $this->util->getWards($districtId);
    }

    public function getOrder(OrderShip $order)
    {
        return $this->util->getOrder($order);
    }

    public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null)
    {
        return $this->util->createOrder($order, $store, $service);
    }

    public function updateOrder(string $code, Order $order, ShippingService $service = null)
    {
        return $this->util->updateOrder($code, $order, $service);
    }

    public function cancelOrder(OrderShip $order)
    {
        return $this->util->cancelOrder($order);
    }

    public function returnOrder(OrderShip $order)
    {
        return $this->util->returnOrder($order);
    }

    public function storingOrder(OrderShip $order)
    {
        return $this->util->storingOrder($order);
    }

    public function printOrders($orders)
    {
        $src = $this->util->printOrders($orders);
        $file_name = sprintf('phieu_gui_hang_%d.html', now()->getTimestamp());
        file_put_contents(storage_path('app/public/' . $file_name), $src);
        return Storage::disk('public')->url($file_name);
    }

    /**
     * @return GiaoHangAbstractUtil
     */
    public function getUtil()
    {
        return $this->util;
    }


}
