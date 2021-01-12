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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::namespace('Api')->name('api.')->group(function() {
    Route::prefix('users')->group(function() {
        Route::get('/', 'UserController@index')->name('index_users');
        Route::post('/store', 'UserController@store')->name('store_users');
        Route::put('/update-access-level', 'UserController@updateAccessLevel')->name('update_access_level_users');
        Route::put('/update-mlearn-id', 'UserController@updateMLearnId')->name('update_mlearn_id_users');
    });
});