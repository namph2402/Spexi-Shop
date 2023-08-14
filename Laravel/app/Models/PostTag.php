<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PostTag extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'post_tags', $this->attributes['slug'],
        );
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag_mapping', 'tag_id', 'post_id');
    }

    public function postViews()
    {
        return $this->belongsToMany(Post::class, 'post_tag_mapping', 'tag_id', 'post_id')->whereStatus(1)->orderBy('order', 'asc');
    }
}
