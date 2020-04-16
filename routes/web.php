<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdministratorAccess;

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

// Google OAuth
if(!empty(env('GOOGLE_CLIENT_ID')))
{
    Route::get('login/google', 'Auth\LoginController@redirectToProviderGoogle');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderGoogleCallback');
}

// Admin Routes
Route::prefix('admin')->namespace('Admin')->as('admin:')->middleware(AdministratorAccess::class)->group(function () {
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
