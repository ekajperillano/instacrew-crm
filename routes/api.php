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

    //User Email
    Route::prefix('user-email')->group(function () { 
        Route::post('/', 'UserEmailController@store');
        Route::get('/{user}/user', 'UserEmailController@byUser');
        Route::patch('/{userEmail}', 'UserEmailController@update');
        Route::delete('/{userEmail}', 'UserEmailController@destroy');
    });

    Route::prefix('client')->group(function () { 
        Route::get('/', 'ClientController@index');
        Route::patch('/{client}/inactive', 'ClientController@inactive');
        Route::patch('/{client}/active', 'ClientController@active');
        Route::patch('/{client}/crew', 'ClientController@setAsCrew');
        Route::get('/{client}', 'ClientController@show');
        Route::patch('/{client}', 'ClientController@update');
        Route::post('/', 'ClientController@store');
        Route::delete('/{client}', 'ClientController@destroy');
    });
    
    Route::prefix('social')->group(function () { 
        Route::get('/', 'SocialController@index');
        Route::get('/{social}', 'SocialController@show');
        Route::patch('/{social}', 'SocialController@update');
        Route::delete('/{social}', 'SocialController@destroy');
        Route::post('/', 'SocialController@store');
    });

    Route::prefix('contact')->group(function () { 
        Route::get('/', 'ClientContactController@index');
        Route::get('/{contact}', 'ClientContactController@show');
        Route::patch('/{contact}', 'ClientContactController@update');
        Route::delete('/{contact}', 'ClientContactController@destroy');
        Route::post('/', 'ClientContactController@store');
    });

    Route::prefix('note')->group(function () { 
        Route::post('/', 'ClientNoteController@store');
    });

    Route::get('permission/list', 'PermissionController@list');

    //notification
    Route::get('users/{user}/notification/unread/count', 'UserNotificationController@unreadCount');
    Route::get('users/{user}/notification', 'UserNotificationController@notifications');
    Route::patch('notification/all/read', 'UserNotificationController@readAll');
    Route::patch('notification/{id}/read', 'UserNotificationController@read');
    Route::get('notification/{id}/show', 'UserNotificationController@show');
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

Route::group(['middleware' => 'foreigner'], function(){
    Route::prefix('foreign')->group(function () { 
        Route::post('client', 'Foreign\ClientController@store');    
    });
});