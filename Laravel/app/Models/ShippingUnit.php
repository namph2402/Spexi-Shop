<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingUnit extends Model
{
    protected $guarded = [];

    public function shipping_strores()
    {
        return $this->hasMany(ShippingStore::class, 'unit_id');
    }

    public function shipping_services()
    {
        return $this->hasMany(ShippingService::class, 'unit_id');
    }

}
