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

Auth::routes();


Route::prefix('cart')->middleware('auth')->group(function(){
    Route::get('/', 'CartController@index')->name('cart');
    Route::post('/add/{barang_id}', 'CartController@add')->name('cart.add');
    Route::get('/delete/{barang_id}', 'CartController@delete')->name('cart.delete');
    Route::get('/purchase', 'CartController@purchase')->name('cart.purchase');
});

Route::prefix('profile')->middleware('auth')->group(function(){
    Route::get('/', 'ProfileController@index')->name('profile');
    Route::post('/store', 'ProfileController@store')->name('profile.store');
    Route::post('/password', 'ProfileController@password')->name('profile.password');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/{barang_id}', 'HomeController@detail')->name('detail');
