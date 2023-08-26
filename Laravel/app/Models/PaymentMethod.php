<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentMethod extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ["data_config"];

    public function getDataConfigAttribute()
    {
        return json_decode($this->attributes['config'], true);
    }
}
