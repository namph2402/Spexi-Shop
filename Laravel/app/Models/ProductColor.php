<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $guarded = [];

    public function warehouse()
    {
        return $this->hasMany(Warehouse::class, 'color_id');
    }
}
