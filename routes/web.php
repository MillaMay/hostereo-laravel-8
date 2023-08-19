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

Route::get('/', function () {
    return view('main');
});

Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');

Route::view('/invalid_link', 'invalid_link')->name('invalid_link');

Route::get('/home/{token}', 'App\Http\Controllers\HomeController@showHome')->name('home');
Route::get('/home/generate/{token}', 'App\Http\Controllers\HomeController@generateLink')->name('generate_link');
Route::get('/deactivate_link/{token}', 'App\Http\Controllers\HomeController@deactivateLink')->name('deactivate_link');
Route::get('/get-history/{token}', 'App\Http\Controllers\HomeController@getHistory')->name('get_history');
