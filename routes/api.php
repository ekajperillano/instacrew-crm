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

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::prefix('users')->group(function () { 
        Route::group(['middleware' => 'isAdmin'], function(){
            Route::get('/', 'UserController@index')->middleware('isAdmin');
            Route::post('/invite', 'UserController@invite')->middleware('isAdmin');
        });
        
        Route::group(['middleware' => 'isAdminOrSelf'], function(){
            Route::get('/{id}', 'UserController@show')->middleware('isAdminOrSelf');
            Route::patch('/{user}/password', 'UserController@passwordUpdate')->middleware('isAdminOrSelf');
            Route::patch('/{user}', 'UserController@update')->middleware('isAdminOrSelf');
        });
    });
    
    //Role
    Route::prefix('role')->group(function () { 
        Route::get('options', 'RoleController@options');
        Route::post('/', 'RoleController@store');
        Route::patch('{role}', 'RoleController@update');
    });
    
    Route::get('permission/list', 'PermissionController@list');
});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});