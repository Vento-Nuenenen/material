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

Route::redirect('/', '/overwatch', 301);

Auth::routes(['register' => false]);

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/overwatch', 'OverwatchController@index')->name('overwatch');

    Route::resource('photos', 'PhotoController');
});
