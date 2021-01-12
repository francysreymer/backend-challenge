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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->name('api.')->group(function() {
    Route::prefix('properties')->group(function() {
        Route::get('/', 'PropertyController@index')->name('index_properties');

        Route::post('/store', 'PropertyController@store')->name('store_properties');

        Route::get('/delete/{id}', 'PropertyController@delete')->name('delete_properties');
    });
});

Route::namespace('Api')->name('api.')->group(function() {
    Route::prefix('contracts')->group(function() {
        Route::post('/store', 'ContractController@store')->name('store_contracts');
    });
});