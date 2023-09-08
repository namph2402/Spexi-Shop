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
        $admin0 = new StoreInformation();
        $admin0->name = 'Logo';
        $admin0->value = env('APP_WEB_URL').'/assets/img/private/logo.webp';
        $admin0->save();

        $admin1 = new StoreInformation();
        $admin1->name = 'Name';
        $admin1->value = 'Spexi';
        $admin1->save();

        $admin2 = new StoreInformation();
        $admin2->name = 'Introduce';
        $admin2->value = 'Spexi shop sẽ mang lại cho khách hàng những trải nghiệm mua sắm trực tuyến thú vị từ các thương hiệu thời trang, cam kết chất lượng phục vụ hàng đầu cùng bộ sưu tập quần áo nam nữ với những xu hướng thời trang mới nhất.';
        $admin2->save();

        $admin3 = new StoreInformation();
        $admin3->name = 'Address';
        $admin3->value = 'Số 16 ngõ 18 Nguyễn Khuyến,  Văn Quán, Hà Đông, Hà Nội';
        $admin3->save();

        $admin4 = new StoreInformation();
        $admin4->name = 'Email';
        $admin4->value = 'spexi.shop@gmail.com';
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
        $admin9->value = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4181.6211748681435!2d105.78604134526462!3d20.976561295373973!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ad6572ad39cb%3A0xb38b529979272770!2sTh%E1%BB%9Di%20trang%20SpeXi!5e0!3m2!1svi!2s!4v1691138241463!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"';
        $admin9->save();
    }
}
