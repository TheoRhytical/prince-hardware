<?php

use App\Http\Controllers\Customer\BackupController;
use App\Http\Controllers\Customer\CustomerHistoryController;
use App\Http\Controllers\Customer\CustomerInfoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('web')->group(function () {
    Route::group([
        'prefix' => 'customer',
    ], function() {
        Route::get('/user-info', [CustomerInfoController::class, 'page']);
        Route::get('/history', [CustomerHistoryController::class, 'page']);
        Route::post('/backup', [BackupController::class, 'download']);

        Route::post('/user-info', [CustomerInfoController::class, 'edit'])
            ->name('customer.edit');
        Route::delete('/user-info', [CustomerInfoController::class, 'delete'])
            ->name('customer.delete');
        Route::patch('/card-status', [CustomerInfoController::class, 'updateCardStatus'])
            ->name('customer.card-status');
    });
});
