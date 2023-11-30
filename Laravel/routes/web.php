<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('/sign-in', 'AccountController@index')->name('login');
Route::post('/sign-in', 'AccountController@checkLogin');

Route::get('/sign-up', 'AccountController@signup');
Route::post('/sign-up', 'AccountController@checkSignup');

Route::get('/retrieval', 'AccountController@retrieval');
Route::post('/retrieval', 'AccountController@retrievalPassword');

Route::get('/send-capcha', 'AccountController@sendCapcha');
Route::post('/check-capcha', 'AccountController@checkCapcha');

Route::get('/products', 'ProductController@index');
Route::get('/product_searchs', 'ProductController@search');
Route::get('/product_categories/{slug}', 'ProductCategoryController@detail');
Route::get('/product_tags/{slug}', 'ProductTagController@detail');
Route::get('/product_categories/{category_slug}/products/{slug}', 'ProductController@detail');

Route::get('/posts', 'PostController@index');
Route::get('/post_searchs', 'PostController@search');
Route::get('/post_categories/{slug}', 'PostCategoryController@detail');
Route::get('/post_tags/{slug}', 'PostTagController@detail');
Route::get('/post_categories/{category_slug}/posts/{slug}', 'PostController@detail');

Route::get('/promotions', 'PromotionController@index');
Route::get('/promotions/{slug}', 'PromotionController@detail');

Route::get('/contact','ContactController@index');

Route::get('/districts/{id}', 'ShipController@getDistricts');
Route::get('/wards/{id}', 'ShipController@getWards');
Route::get('/getFee', 'ShipController@getFee');

Route::get('/getWarehouse', 'WarehouseController@getWarehouse');

Route::get('/huong-dan-chon-size', 'HomeController@size');
Route::get('/chinh-sach-bao-mat', 'HomeController@privacy');
Route::get('/chinh-sach-mua-hang', 'HomeController@purchase');

Route::post('/form-data', 'FormDataController@store');

Route::get('/not-found', function () {
    return view('pages.notFound');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/sign-out', 'AccountController@logout');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('', 'UserProfileController@index');
        Route::post('update-profile', 'UserProfileController@store');
        Route::get('password', 'UserProfileController@password');
        Route::post('update-password', 'UserProfileController@updatePassword');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('', 'UserProfileController@order');
        Route::get('cancel/{id}', 'UserProfileController@orderCancel');
        Route::get('detail/{id}', 'UserProfileController@orderDetail');
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('', 'CartController@index');
        Route::post('addItem', 'CartController@addItem');
        Route::get('updateItem/{id}', 'CartController@updateItem');
        Route::get('deleteItem/{id}', 'CartController@deleteItem');
    });

    Route::post('/comment', 'CommentController@store');

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('', 'CheckoutController@index');
        Route::post('order', 'CheckoutController@store');
        Route::get('completed/vnpay', 'CheckoutController@vnpay');
        Route::get('completed/momo', 'CheckoutController@momo');
    });

    Route::group(['prefix' => 'voucher'], function () {
        Route::get('', 'VoucherController@index');
        Route::get('check/{code}', 'VoucherController@check');
    });

    Route::get('/notification', 'NotificationController@index');
    Route::get('/notification/{slug}', 'NotificationController@detail');
});
