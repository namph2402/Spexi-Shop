<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(BannerGroup::class, 'group_id');
    }
}
