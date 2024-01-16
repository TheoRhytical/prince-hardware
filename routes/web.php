<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customer\AnnouncementController;
use App\Http\Controllers\Customer\BackupController;
use App\Http\Controllers\Customer\CustomerHistoryController;
use App\Http\Controllers\Customer\CustomerInfoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    $authenticated = Auth::check();
    return Inertia::render('Welcome', [
        'canLogin' => !$authenticated && Route::has('login'),
        'canRegister' => !$authenticated && Route::has('register'),
        'userType' => $authenticated ? Auth::user()->user_type : '',
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// Route::get('/testing', function() {
//     return Inertia::render('Testing');
// });

Route::middleware(['auth', 'admin'])->group(function () {
    // Route::get('/profile', [ProfileController::class, 'view'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/user-info', [CustomerInfoController::class, 'index'])->name('customer.index');

    Route::get('/history', [CustomerHistoryController::class, 'index'])->name('customer.history');
    Route::patch('/history', [CustomerHistoryController::class, 'update'])->name('customer.history.edit');

    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('customer.announcement');
    Route::get('/backup', [BackupController::class, 'index'])->name('customer.backup');

    // This allows customer signatures to be private and protected with auth
    Route::get('/images/customer_signature/{filename}', [CustomerInfoController::class, 'getSignature'])->name('admin.customer.signature');
});

Route::middleware(['auth', 'customer'])->group(function () {

    Route::get('/profile', [CustomerInfoController::class, 'profile'])
        ->name('customer.profile');
    Route::get('/signature', [CustomerInfoController::class, 'customerGetSignature'])
        ->name('customer.signature');
});

Route::get('/testing', function () {
    dd(\Carbon\Carbon::now()->toDateTimeString());
});

require __DIR__.'/auth.php';
