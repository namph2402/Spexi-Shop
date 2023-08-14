<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RelatedPost extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class, 'related_id');
    }
}
