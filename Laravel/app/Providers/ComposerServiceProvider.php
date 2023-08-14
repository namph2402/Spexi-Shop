<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\MenuGroup;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\StoreInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $categories = ProductCategory::with('childrens')->whereParentId(0)->orderBy('order', 'asc')->get();
            View::share('categories', $categories);

            $sizes = ProductSize::get();
            View::share('sizes', $sizes);

            $colors = ProductColor::get();
            View::share('colors', $colors);

            $menuGoup = MenuGroup::whereName('Main menu')->first();
            $menus = Menu::with('childrens')->whereGroupId($menuGoup->id)->whereParentId(0)->orderBy('order', 'asc')->get();
            View::share('menus', $menus);

            $menuLink = MenuGroup::whereName('Link menu')->first();
            $links = Menu::with('childrens')->whereGroupId($menuLink->id)->whereParentId(0)->orderBy('order', 'asc')->get();
            View::share('links', $links);

            $data = [];
            $store = StoreInformation::get();
            foreach ($store as $s) {
                $data[$s->name] = $s->value;
            }
            $areaCode = substr($data['Phone'], 0, 3);
            $nextThree = substr($data['Phone'], 3, 3);
            $lastFour = substr($data['Phone'], 6, 4);
            $phoneView = '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
            $data['PhoneView'] = $phoneView;
            View::share('data', $data);

        } catch (\Exception $e) {
            Log::error($e);
        }
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
