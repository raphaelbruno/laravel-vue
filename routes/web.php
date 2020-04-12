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

Route::get('/', 'Site\PageController@index');

Auth::routes();

Route::prefix('admin')->namespace('Admin')->as('admin:')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    
    // Media
    Route::get('media/{path}', 'MediaController@index')->where('path', '(.*)');
    
    // Profile
    Route::get('profile', 'ProfileController@edit')->name('profile');
    Route::match(['PUT', 'PATCH'], 'profile/update', 'ProfileController@update')->name('profile.update');
    
    // Access Control List
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');

    // CRUDs
    Route::resource('foos', 'FooController');
});
