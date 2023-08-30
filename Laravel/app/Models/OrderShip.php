<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShip extends Model
{
    public static $DANG_GIAO = "Đang giao";
    public static $GIAO_LAI = "Giao lại";
    public static $HOAN_THANH = "Hoàn thành";
    public static $HUY_DON = "Hủy đơn";

    protected $guarded = [];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function unit()
    {
        return $this->belongsTo(ShippingUnit::class, 'unit_id');
    }

    public function store()
    {
        return $this->belongsTo(ShippingStore::class, 'store_id');
    }

    public function service()
    {
        return $this->belongsTo(ShippingService::class, 'service_id');
    }
}
