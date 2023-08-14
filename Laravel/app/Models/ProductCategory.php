<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'product_categories', $this->attributes['slug'],
        );
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function productViews()
    {
        return $this->hasMany(Product::class, 'category_id')->whereStatus(true);
    }
}
