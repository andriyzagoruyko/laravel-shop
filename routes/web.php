<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;



use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyOptionController;



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

Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout');
Route::get('/reset', [ResetController::class, 'reset'])->name('reset');

Route::group(['middleware' => 'auth'], function() {
    Route::group([
        'prefix' => 'person',
        'as' => 'person.'
    ], function() {
        Route::get('/orders', 'App\Http\Controllers\Person\OrderController@index')->name('orders.index');
        Route::get('/orders/{order}', 'App\Http\Controllers\Person\OrderController@shows')->name('orders.show');
    });

    Route::group([
        'middleware' => 'is_admin',
        'prefix' => 'admin',
    ], function() {
        Route::get('/orders', [OrderController::class, 'index'])->name('home');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('products/{product}/skus', SkuController::class);
        Route::resource('properties', PropertyController::class);
        Route::resource('properties/{property}/property-options', PropertyOptionController::class);
    });
});

Route::group(['prefix' => 'basket'], function() {
    Route::post('/add/{product}',  [BasketController::class, 'basketAdd'])->name("basket-add");
    Route::group([
        'middleware' => 'basket_not_empty',
        ], 
        function() {
            Route::get('/',  [BasketController::class, 'basket'])->name("basket");
            Route::get('/place',  [BasketController::class, 'basketPlace'])->name("basket-place");
            Route::post('/place',  [BasketController::class, 'basketConfirm'])->name("basket-confirm");
            Route::post('/remove/{product}',  [BasketController::class, 'basketRemove'])->name("basket-remove");
    });
});

Route::get('/locale/{locale}',  [MainController::class, 'changeLocale'])->name("locale");
Route::get('/currency/{currencyCode}',  [MainController::class, 'changeCurrency'])->name("currency");

Route::get('/',  [MainController::class, 'index'])->name("index");
Route::get('/categories',  [MainController::class, 'categories'])->name("categories");
Route::get('/{category}',  [MainController::class, 'singleCategory'])->name("category");
Route::get('/{category}/{product?}',  [MainController::class, 'singleProduct'])->name("product");
Route::post('subscription/{product}',  [MainController::class, 'subscribe'])->name("subscription");

