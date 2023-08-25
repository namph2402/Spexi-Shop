<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'LoginController@login');

Route::group(['middleware' => ['auth.admin']], function () {

    Route::resource('dashboard', 'DashboardController');

    Route::resource('stores', 'StoreInformationController');

    Route::resource('store_posts', 'StorePostController');

    // Staff
    Route::resource('staffs', 'StaffController');
    Route::group(['prefix' => 'staffs/{id}'], function () {
        Route::post('enable', 'StaffController@enable');
        Route::post('disable', 'StaffController@disable');
        Route::post('repassword', 'StaffController@repassword');
    });

    // User
    Route::resource('users', 'UserController');
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('enable', 'UserController@enable');
        Route::post('disable', 'UserController@disable');
    });

    // Banner
    Route::resource('banner_groups', 'BannerGroupController');
    Route::resource('banners', 'BannerController')->except('update');
    Route::group(['prefix' => 'banners/{id}'], function () {
        Route::post('', 'BannerController@update');
        Route::post('enable', 'BannerController@enable');
        Route::post('disable', 'BannerController@disable');
        Route::post('up', 'BannerController@up');
        Route::post('down', 'BannerController@down');
    });

    // Menu
    Route::resource('menu_groups', 'MenuGroupController');
    Route::resource('menus', 'MenuController');
    Route::group(['prefix' => 'menus/{id}'], function () {
        Route::post('up', 'MenuController@up');
        Route::post('down', 'MenuController@down');
    });

    // Post category
    Route::resource('post_categories', 'PostCategoryController');
    Route::group(['prefix' => 'post_categories/{id}'], function () {
        Route::post('up', 'PostCategoryController@up');
        Route::post('down', 'PostCategoryController@down');
    });

    // Product category
    Route::resource('product_categories', 'ProductCategoryController')->except(['update']);
    Route::group(['prefix' => 'product_categories/{id}'], function () {
        Route::post('', 'ProductCategoryController@update');
        Route::post('up', 'ProductCategoryController@up');
        Route::post('down', 'ProductCategoryController@down');
    });

    // Product
    Route::get('products/loadTag', 'ProductController@loadTag');
    Route::post('products/import', 'ProductController@import');
    Route::get('products/export', 'ProductController@export');
    Route::resource('products', 'ProductController')->except(['update']);
    Route::group(['prefix' => 'products/{id}'], function () {
        Route::post('', 'ProductController@update');
        Route::post('enable', 'ProductController@enable');
        Route::post('disable', 'ProductController@disable');
        Route::post('up', 'ProductController@up');
        Route::post('down', 'ProductController@down');
        Route::post('attach_tags', 'ProductController@attachTags');
        Route::post('detach_tags', 'ProductController@detachTags');
        Route::get('loadAvailableProducts', 'ProductController@loadAvailableProducts');
    });

    // Product tag
    Route::resource('product_tags', 'ProductTagController');
    Route::group(['prefix' => 'product_tags/{id}'], function () {
        Route::post('up', 'ProductTagController@up');
        Route::post('down', 'ProductTagController@down');
        Route::post('attach_tags', 'ProductTagController@attachTags');
        Route::post('detach_tags', 'ProductTagController@detachTags');
    });

    // Product variant
    Route::resource('product_sizes', 'ProductSizeController');
    Route::resource('product_colors', 'ProductColorController');

    // Product image
    Route::resource('product_images', 'ProductImageController')->except('update');
    Route::group(['prefix' => 'product_images/{id}'], function () {
        Route::post('enable', 'ProductImageController@enable');
        Route::post('disable', 'ProductImageController@disable');
        Route::post('up', 'ProductImageController@up');
        Route::post('down', 'ProductImageController@down');
    });

    // Warehouse
    Route::post('warehouses/import', 'WarehouseController@import');
    Route::get('warehouses/export', 'WarehouseController@export');
    Route::resource('warehouses', 'WarehouseController');
    Route::group(['prefix' => 'warehouses/{id}'], function () {
        Route::post('enable', 'WarehouseController@enable');
        Route::post('disable', 'WarehouseController@disable');
    });

    // Promotion
    Route::resource('promotions', 'PromotionController')->except(['update']);
    Route::group(['prefix' => 'promotions/{id}'], function () {
        Route::post('', 'PromotionController@update');
        Route::post('enable', 'PromotionController@enable');
        Route::post('disable', 'PromotionController@disable');
        Route::post('attach_products', 'PromotionController@attachProducts');
        Route::post('detach_products', 'PromotionController@detachProducts');
        Route::get('loadProduct', 'PromotionController@loadProduct');
        Route::post('updateSalePrice', 'PromotionController@updateSalePrice');
    });
    Route::group(['prefix' => 'promotion_product_mappings'], function () {
        Route::get('', 'PromotionProductMappingController@index');
        Route::get('loadProduct', 'PromotionProductMappingController@loadProduct');
    });

    // Related product
    Route::group(['prefix' => 'related_products'], function () {
        Route::get('loadProduct', 'RelatedProductController@loadProduct');
        Route::post('{id}/up', 'RelatedProductController@up');
        Route::post('{id}/down', 'RelatedProductController@down');
    });
    Route::resource('related_products', 'RelatedProductController');

    // Article
    Route::resource('articles', 'ArticleController')->only('store');
    Route::post('articles/{id}', 'ArticleController@update');

    //Comment
    Route::resource('comments', 'CommentController');
    Route::group(['prefix' => 'comments/{id}'], function () {
        Route::post('enable', 'CommentController@enable');
        Route::post('disable', 'CommentController@disable');
    });

    //Post
    Route::get('posts/loadTag', 'PostController@loadTag');
    Route::resource('posts', 'PostController')->except(['update']);
    Route::group(['prefix' => 'posts/{id}'], function () {
        Route::post('', 'PostController@update');
        Route::post('enable', 'PostController@enable');
        Route::post('disable', 'PostController@disable');
        Route::post('up', 'PostController@up');
        Route::post('down', 'PostController@down');
        Route::post('attach_tags', 'PostController@attachTags');
        Route::post('detach_tags', 'PostController@detachTags');
    });

    //Post tag
    Route::resource('post_tags', 'PostTagController');
    Route::group(['prefix' => 'post_tags/{id}'], function () {
        Route::post('up', 'PostTagController@up');
        Route::post('down', 'PostTagController@down');
        Route::post('attach_tags', 'PostTagController@attachTags');
        Route::post('detach_tags', 'PostTagController@detachTags');
    });

    // Related post
    Route::group(['prefix' => 'related_posts'], function () {
        Route::get('loadPost', 'RelatedPostController@loadPost');
        Route::post('{id}/up', 'RelatedPostController@up');
        Route::post('{id}/down', 'RelatedPostController@down');
    });
    Route::resource('related_posts', 'RelatedPostController');

    //Form data
    Route::resource('form_datas', 'FormDataController');

    //Notification
    Route::resource('notifications', 'NotificationController');
    Route::group(['prefix' => 'notifications/{id}'], function () {
        Route::post('enable', 'NotificationController@enable');
        Route::post('disable', 'NotificationController@disable');
    });
    // Order
    Route::resource('orders', 'OrderController');
    Route::group(['prefix' => 'orders/{id}'], function () {
        Route::post('confirm', 'OrderController@confirm');
        Route::post('cancel', 'OrderController@cancel');
    });
    Route::resource('order_details', 'OrderDetailController');

    //Order ship
    Route::get('order_ships/printBills', 'OrderShipController@PrintBills');
    Route::resource('order_ships', 'OrderShipController');
    Route::group(['prefix' => 'order_ships/{id}'], function () {
        Route::get('shipping', 'OrderShipController@shipping');
        Route::get('complete', 'OrderShipController@complete');
        Route::get('printBill', 'OrderShipController@printBill');
    });

    // Voucher
    Route::resource('vouchers', 'VoucherController');
    Route::group(['prefix' => 'vouchers/{id}'], function () {
        Route::post('enable', 'VoucherController@enable');
        Route::post('disable', 'VoucherController@disable');
    });

    // Shipping fee
    Route::resource('provinces', 'ProvinceController');
    Route::resource('districts', 'DistrictController');
    Route::resource('wards', 'WardController');
    Route::resource('shipping_fees', 'ShippingFeeController');

    // Shipping unit
    Route::resource('shipping_units', 'ShippingUnitController');
    Route::group(['prefix' => 'shipping_units'], function () {
        Route::post('createUnitPartner', 'ShippingUnitController@createUnitPartner');
        Route::post('{id}/synchronized', 'ShippingUnitController@synchronized');
    });
    Route::resource('shipping_services', 'ShippingServiceController');
    Route::resource('shipping_stores', 'ShippingStoreController');

    Route::resource('imports', 'ImportController');
});
