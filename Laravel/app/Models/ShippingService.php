<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];
}
