<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionProductMapping extends Model
{
    protected $table = 'promotion_product_mapping';
    public $timestamps = false;
    protected $guarded = [];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
