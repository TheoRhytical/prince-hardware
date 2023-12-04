<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
], function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');

    Route::middleware('auth:api')->group(function() {
        Route::post('/refresh', 'refresh');
        Route::delete('/logout', 'logout');
    });
});

Route::middleware('auth:api')->group(function() {
    // Place 
});