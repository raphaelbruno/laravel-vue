<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdministratorAccess;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;

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

Route::get('/', [PageController::class, 'index']);

Auth::routes();

// Google OAuth
if(!empty(env('GOOGLE_CLIENT_ID')))
{
    Route::get('login/google', [LoginController::class, 'redirectToProviderGoogle']);
    Route::get('login/google/callback', [LoginController::class, 'handleProviderGoogleCallback']);
}

// Admin Routes
Route::prefix('admin')->namespace('Admin')->as('admin:')->middleware(AdministratorAccess::class)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Media
    Route::get('media/{path}', [MediaController::class, '@index'])->where('path', '(.*)');
    
    // Messages
    Route::get('messages/{session}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{session}/send', [MessageController::class, 'send'])->name('messages.send');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
    Route::match(['PUT', 'PATCH'], 'profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/toggleDarkMode', [ProfileController::class, 'toggleDarkMode'])->name('profile.toggleDarkMode');
    
    Route::resources([
        // Access Control List
        'users' => UserController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,

        // CRUDs
        'foos' => FooController::class,
    ]);

});
