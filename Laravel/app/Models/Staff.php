<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
