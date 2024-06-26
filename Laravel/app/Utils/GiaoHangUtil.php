<?php

namespace App\Utils;

use App\Models\Order;
use App\Models\OrderShip;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;
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

    public function getServices()
    {
        return $this->util->getServices();
    }

    public function getStores()
    {
        return $this->util->getStores();
    }

    public function getOrder(OrderShip $order)
    {
        return $this->util->getOrder($order);
    }

    public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null)
    {
        return $this->util->createOrder($order, $store, $service);
    }

    public function cancelOrder(OrderShip $order)
    {
        return $this->util->cancelOrder($order);
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
