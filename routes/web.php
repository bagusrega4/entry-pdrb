<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ManageUserController;

// -------------------------------------------------------------------
// Halaman Home
// -------------------------------------------------------------------
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// -------------------------------------------------------------------
// Auth & Verifikasi
// -------------------------------------------------------------------
require __DIR__ . '/auth.php';

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User is authenticated: ' . Auth::user()->name;
    } else {
        return 'User is not authenticated.';
    }
})->name('check-auth');

// -------------------------------------------------------------------
// Halaman error jika unauthorized
// -------------------------------------------------------------------
Route::get('/notfound', function () {
    return view('error.unauthorized');
})->name('error.unauthorized');

// -------------------------------------------------------------------
// Lolos 'auth' dan 'verified'
// -------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware('role:1,2')->group(function () {

        // Dashboard
        Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])
            ->name('dashboard.admin');

        Route::get('/dashboardOperator', [DashboardOperatorController::class, 'index'])
            ->name('dashboard.operator');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/edit-profile', [ProfileController::class, 'setPhotoProfile'])->name('edit.profile');
        Route::put('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');

        // Harga
        Route::prefix('prices')->group(function () {
            Route::get('/', [PriceController::class, 'index'])->name('prices.index');
            Route::get('/commodities/{id}/children', [PriceController::class, 'getChildren']);
            Route::get('/indicators', [PriceController::class, 'getIndicators'])->name('prices.indicators');
            Route::get('/units', [PriceController::class, 'getUnits'])->name('prices.units');
            Route::post('/', [PriceController::class, 'store'])->name('prices.store');
        });

        // Produksi
        Route::prefix('productions')->group(function () {
            Route::get('/', [ProductionController::class, 'index'])->name('productions.index');
            Route::get('/commodities/{id}/children', [ProductionController::class, 'getChildren']);
            Route::get('/indicators', [ProductionController::class, 'getIndicators'])->name('productions.indicators');
            Route::get('/units', [ProductionController::class, 'getUnits'])->name('productions.units');
            Route::post('/', [ProductionController::class, 'store'])->name('productions.store');
        });

        // Manage User
        Route::name('manage.user.')->prefix('manage/user')->group(function () {
            Route::get('/', [ManageUserController::class, 'index'])->name('index');
            Route::get('/create', [ManageUserController::class, 'create'])->name('create');
            Route::post('/store', [ManageUserController::class, 'store'])->name('store');
            Route::put('/{id}/update-role', [ManageUserController::class, 'updateRoleUser'])->name('updateRole');
            Route::put('/{id}/update-tim', [ManageUserController::class, 'updateTimUser'])->name('updateTim'); // 👉 tambahan
            Route::get('/edit/{id}', [ManageUserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ManageUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [ManageUserController::class, 'destroy'])->name('destroy');
        });
    });
});
