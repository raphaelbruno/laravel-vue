<?php

use App\Http\Controllers\Api\AuthController;
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

if(env('ENABLE_API')){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->prefix('/')->group(function(){
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/logout', [AuthController::class, 'logout']);

        Route::resources([
            'users' => Admin\UserController::class,
            'roles' => Admin\RoleController::class,
            'permissions' => Admin\PermissionController::class,
        ]);
    });
}