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

Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/get_user', 'AuthController@get_user');
    Route::post('/change_lang', 'AuthController@change_lang');

    Route::get('/user', 'UserController@get_user_list');
    Route::post('/user', 'UserController@add_user')
        ->middleware('check.authority:3');
    Route::put('/user/{id}', 'UserController@edit_user')
        ->middleware('check.authority:3', 'check.id:App\User');
    Route::patch('/user/{id}', 'UserController@resign_user')
        ->middleware('check.authority:3', 'check.id:App\User');
    Route::post('/user/password_reset/{id}', 'UserController@reset_password')
        ->middleware('check.authority:3', 'check.id:App\User');

    Route::get('/group', 'GroupController@get_group_list');
    Route::post('/group', 'GroupController@add_group')
        ->middleware('check.authority:2');
    Route::put('/group/{id}', 'GroupController@edit_group')
        ->middleware('check.authority:2', 'check.id:App\Group');
    Route::patch('/group/{id}', 'GroupController@edit_group_member')
        ->middleware('check.authority:2', 'check.id:App\Group');
    Route::delete('/group/{id}', 'GroupController@remove_group')
        ->middleware('check.authority:2', 'check.id:App\Group');
});