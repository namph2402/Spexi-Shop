<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
