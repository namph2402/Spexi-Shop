<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = [];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
