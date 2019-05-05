<?php

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

Route::get('/', 'SuccessController@index')->name('index');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'SuccessController@index')->name('home');
    Route::get('/success', 'SuccessController@index')->name('home');
    Route::any('/participations', 'ParticipationsController@index')->name('participations');
    Route::get('/participations/add', 'ParticipationsController@create')->name('add-participations');
    Route::post('/participations/store', 'ParticipationsController@store')->name('store-participations');
    Route::post('participations/import', 'ParticipationsController@import')->name('import-participations');
    Route::get('/participations/edit/{pid}', 'ParticipationsController@edit')->name('edit-participations');
    Route::post('/participations/update/{pid}', 'ParticipationsController@update')->name('update-participations');
    Route::get('/participations/destroy/{pid}', 'ParticipationsController@destroy')->name('destroy-participations');
    Route::any('/users', 'UsersController@index')->name('users');
    Route::get('/users/add', 'UsersController@create')->name('add-users');
    Route::post('/users/store', 'UsersController@store')->name('store-users');
    Route::get('/users/edit/{uid}', 'UsersController@edit')->name('edit-users');
    Route::post('/users/update/{uid}', 'UsersController@update')->name('update-users');
    Route::get('/users/destroy/{uid}', 'UsersController@destroy')->name('destroy-users');
    Route::any('/groups', 'GroupsController@index')->name('groups');
    Route::get('/groups/add', 'GroupsController@create')->name('add-groups');
    Route::post('/groups/store', 'GroupsController@store')->name('store-groups');
    Route::get('/groups/edit/{gid}', 'GroupsController@edit')->name('edit-groups');
    Route::post('/groups/update/{gid}', 'GroupsController@update')->name('update-groups');
    Route::get('/groups/destroy/{gid}', 'GroupsController@destroy')->name('destroy-groups');
    Route::any('/fields', 'FieldsController@index')->name('fields');
    Route::get('/fields/add', 'FieldsController@create')->name('add-fields');
    Route::post('/fields/store', 'FieldsController@store')->name('store-fields');
    Route::get('/fields/edit/{fid}', 'FieldsController@edit')->name('edit-fields');
    Route::post('/fields/update/{fid}', 'FieldsController@update')->name('update-fields');
    Route::get('/fields/destroy/{fid}', 'FieldsController@destroy')->name('destroy-fields');
    Route::any('/points', 'PointsController@index')->name('points');
    Route::get('/points/add', 'PointsController@create')->name('add-points');
    Route::post('/points/store', 'PointsController@store')->name('store-points');
    Route::get('/points/edit/{poid}', 'PointsController@edit')->name('edit-points');
    Route::post('/points/update/{poid}', 'PointsController@update')->name('update-points');
    Route::get('/points/destroy/{poid}', 'PointsController@destroy')->name('destroy-points');
    Route::get('/gratulation', 'GratulationController@index')->name('gratulation');
    Route::post('/gratulation/create', 'GratulationController@create')->name('create-gratulation');
});
