<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $guarded = [];

    public function warehouse()
    {
        return $this->hasMany(Warehouse::class, 'size_id');
    }
}
