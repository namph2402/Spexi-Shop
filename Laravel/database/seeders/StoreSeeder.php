<?php

namespace Database\Seeders;

use App\Models\StoreInformation;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * php artisan db:seed --class=StoreSeeder
     *
     * @return void
     */
    public function run()
    {
        $admin1 = new StoreInformation();
        $admin1->name = 'Name';
        $admin1->value = 'Spexi';
        $admin1->save();

        $admin2 = new StoreInformation();
        $admin2->name = 'Introduce';
        $admin2->value = 'Thời trang spexi';
        $admin2->save();

        $admin3 = new StoreInformation();
        $admin3->name = 'Address';
        $admin3->value = 'Spexi';
        $admin3->save();

        $admin4 = new StoreInformation();
        $admin4->name = 'Email';
        $admin4->value = 'nam@gmail.com';
        $admin4->save();

        $admin5 = new StoreInformation();
        $admin5->name = 'Phone';
        $admin5->value = '0356645052';
        $admin5->save();

        $admin6 = new StoreInformation();
        $admin6->name = 'Facebook';
        $admin6->value = 'https://www.facebook.com/thoitrangspexi';
        $admin6->save();

        $admin7 = new StoreInformation();
        $admin7->name = 'Instagram';
        $admin7->value = 'https://www.instagram.com/spexi.fashion';
        $admin7->save();

        $admin8 = new StoreInformation();
        $admin8->name = 'Tiktok';
        $admin8->value = 'https://www.tiktok.com/@thoitrangspexi';
        $admin8->save();

        $admin9 = new StoreInformation();
        $admin9->name = 'Map';
        $admin9->value = 'https://www.tiktok.com/@thoitrangspexi';
        $admin9->save();
    }
}
