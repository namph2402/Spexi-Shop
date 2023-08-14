<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'related_id');
    }

    public function productView()
    {
        return $this->belongsTo(Product::class, 'related_id')->whereStatus(true);
    }
}
