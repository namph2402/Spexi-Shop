<?php

namespace App\Models;


use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Promotion extends Model
{
    public static $GIAM_SAN_PHAM = '1';
    public static $DONG_GIA = '2';
    public static $FREE_SHIP = '3';
    public static $GIAM_DON_HANG = '4';

    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'promotions', $this->attributes['slug'],
        );
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product_mapping');
    }

    public function mapping()
    {
        return $this->hasMany(PromotionProductMapping::class);
    }
}
