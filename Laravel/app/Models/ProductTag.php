<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ProductTag extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'product_tags', $this->attributes['slug'],
        );
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_mapping', 'tag_id', 'product_id');
    }

    public function productViews()
    {
        return $this->belongsToMany(Product::class, 'product_tag_mapping', 'tag_id', 'product_id')->whereStatus(1)->orderBy('order', 'asc');
    }
}
