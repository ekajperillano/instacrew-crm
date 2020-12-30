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

Route::get('mail', function () {
    $user = App\Models\User::find(1);
    return new App\Mail\User\InviteUser($user, '2222');
});

Route::get('/{any?}', function (){
    return view('index');
})->where('any', '^(?!api\/)[\/\w\.-]*');
