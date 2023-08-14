<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{
    protected $fillable = [
        'name', 'avatar', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
