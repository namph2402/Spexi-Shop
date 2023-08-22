<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sizes()
    {
        return $this->hasOne(ProductSize::class, 'id', 'size_id');
    }

    public function colors()
    {
        return $this->hasOne(ProductColor::class, 'id', 'color_id');
    }
}
