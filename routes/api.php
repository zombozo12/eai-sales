<?php

use Illuminate\Http\Request;
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

Route::prefix('auth')->group(function(){
    Route::post('/login', 'API\AuthController@login')->name('auth.login');
    Route::post('/register', 'API\AuthController@register')->name('auth.register');
    Route::get('/logout', 'API\AuthController@logout')->name('auth.logout')->middleware(['auth:api']);
});

Route::prefix('customer')->middleware(['auth:api'])->group(function(){
    Route::get('/', 'API\CustomerController@getCurrent')->name('customer.current');
    Route::get('/{customer_id}', 'API\CustomerController@getByID')->name('customer.byid');
    Route::post('/create', 'API\CustomerController@store')->name('customer.store');
    Route::post('/update/{customer_id}', 'API\CustomerController@update')->name('customer.update');
    Route::get('/delete/{customer_id}', 'API\CustomerController@delete')->name('customer.delete');
});

Route::prefix('transaction')->group(function(){
    Route::get('/', 'API\TransactionController@getAll')->name('transaction.all');
    Route::get('/{id}', 'API\TransactionController@getByID')->name('transaction.byid');
    Route::post('/create', 'API\TransactionController@store')->name('transaction.store')->middleware(['auth:api']);
});

Route::prefix('advertisement')->group(function(){
    Route::get('/', 'API\AdvertisementController@index')->name('advertisement.all');
    Route::get('/{id}', 'API\AdvertisementController@getByID')->name('advertisement.byid');
    Route::post('/create', 'API\AdvertisementController@create')->name('advertisement.create');
});
