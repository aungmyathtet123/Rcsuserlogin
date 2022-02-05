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

Route::group(['middleware'=>['CORS']],function() {
    Route::post('/user/login','Api\UserController@login');
    Route::post('/user/register','Api\UserController@register');

});

Route::group(['middleware'=>['CORS','auth:api']],function(){
    Route::get('/userlist','Api\UserController@getuserlist');
});
