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
Route::group(['namespace' => 'Authenticate'], function () {
    Route::post('/authenticate', 'AuthController@authenticate');
    Route::post('/register', 'AuthController@register');
    Route::post('/reset_code', 'AuthController@getResetCode');
    Route::post('/reset_password', 'AuthController@resetPassword');
});

Route::group(['namespace' => 'Users'], function () {
    Route::get('/user_types', 'UsersController@getUserTypes');
    Route::get('/users', 'UsersController@getUsers');
    Route::get('/users_paginated', 'UsersController@getPaginatedUsers');
    Route::post('/user', 'UsersController@create');
    Route::post('/user_status', 'UsersController@changeUserStatus');
    Route::patch('/user', 'UsersController@update');
    Route::delete('/user/{id}', 'UsersController@delete');
});

Route::group(['namespace' => 'MessagePorts'], function () {
    Route::get('/message_ports', 'MessagePortsController@getMessagePortList');
    Route::get('/message_ports_paginated', 'MessagePortsController@getMessagePortsPaginated');
    Route::post('/message_port', 'MessagePortsController@create');
    Route::patch('/message_port', 'MessagePortsController@update');
    Route::delete('/message_port/{id}', 'MessagePortsController@delete');
});
