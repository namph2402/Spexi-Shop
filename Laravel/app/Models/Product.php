<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

class Product extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'product_categories', $this->attributes['category_slug'],
            'products', $this->attributes['slug']
        );
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function warehouseViews()
    {
        return $this->hasMany(Warehouse::class)->where('status', '=', 1)->where('quantity', '>', 0);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_mapping', 'product_id', 'tag_id');
    }

    public function comments()
    {
        return $this->public_comments()->whereStatus(true)->orderBy('id', 'desc');
    }

    public function public_comments()
    {
        return $this->hasManyThrough(Comment::class, Article::class, 'articleable_id')
            ->where('articleable_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

    public function relateds()
    {
        return $this->hasMany(RelatedProduct::class)->orderBy('order', 'asc');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product_mapping');
    }
}
