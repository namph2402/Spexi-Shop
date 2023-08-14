<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
