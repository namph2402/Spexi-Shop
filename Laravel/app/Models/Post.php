<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

class Post extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'post_categories', $this->attributes['category_slug'],
            'posts', $this->attributes['slug']
        );
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function article()
    {
        return $this->morphOne(Article::class, 'articleable');
    }

    public function tags()
    {
        return $this->belongsToMany(PostTag::class, 'post_tag_mapping', 'post_id', 'tag_id');
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
        return $this->hasMany(RelatedPost::class)->orderBy('order', 'asc');
    }
}
