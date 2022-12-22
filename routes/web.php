<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\WebhookController;
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

Route::get('/', function () {
    //    dd(  auth()->user());
    return view('welcome');
});
//Route::controller(MerchantController::class)->group(function(){

//Route::get('orders',[MerchantController::class, 'order'])->name('orders.order..');

//    Route::get('/order', 'order')->name('order');



//});

//Route::get('/courses/order', 'MerchantController@order');
//Route::get('/course', 'MerchantController@index');



Route::get('/merchant/order-stats', [MerchantController::class, 'orderStats'])->name('merchant.order-stats');
Route::post('/webhook', WebhookController::class)->name('webhook');

