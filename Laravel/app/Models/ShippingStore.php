<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingStore extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];
}
