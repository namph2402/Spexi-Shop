<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BannerGroup extends Model
{
    protected $guarded = [];

    public function banners()
    {
        return $this->hasMany(Banner::class, 'group_id')->orderBy('order', 'asc');
    }
}
