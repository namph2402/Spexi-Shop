<?php

namespace App\Models;

use App\Utils\StringUtil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PostCategory extends Model
{
    protected $guarded = [];

    protected $appends = [
        'full_path',
    ];

    public function getFullPathAttribute()
    {
        return StringUtil::joinPaths(Config::get('app.web_url'),
            'post_categories', $this->attributes['slug'],
        );
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
