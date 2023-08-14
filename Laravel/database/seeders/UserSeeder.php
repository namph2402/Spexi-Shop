<?php

namespace Database\Seeders;

use App\Models\SystemUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * php artisan db:seed --class=UserSeeder
     *
     * @return void
     */
    public function run()
    {
        $admin = new SystemUser();
        $admin->name = 'Admin';
        $admin->username = 'admin';
        $admin->avatar = 'http://localhost:8000/assets/img/private/admin.png';
        $admin->password = Hash::make('123456a@');
        $admin->remember_token = Str::random(100);
        $admin->save();
    }
}
