<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShip extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->hasOne(Order::class, 'order_id');
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
