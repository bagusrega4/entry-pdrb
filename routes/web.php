<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PriceProductionController;
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

        Route::prefix('prices-productions')->group(function () {
            Route::get('/', [PriceProductionController::class, 'index'])
                ->name('prices_productions.index');

            // ambil subtree (anak-anak komoditas)
            Route::get('/commodities/{commodity}/subtree', [PriceProductionController::class, 'getSubtree'])
                ->name('prices_productions.subtree');

            // generate kode otomatis untuk child baru
            Route::get('/commodities/{parent}/next-code', [PriceProductionController::class, 'generateNextCode'])
                ->name('prices_productions.next_code');

            // indikator & satuan
            Route::get('/indicators', [PriceProductionController::class, 'getIndicators'])
                ->name('prices_productions.indicators');
            Route::get('/unit-harga', [PriceProductionController::class, 'getUnitHarga'])
                ->name('prices_productions.unit_harga');
            Route::get('/unit-produksi', [PriceProductionController::class, 'getUnitProduksi'])
                ->name('prices_productions.unit_produksi');

            // tambah komoditas baru
            Route::post('/commodities', [PriceProductionController::class, 'storeCommodity'])
                ->name('prices_productions.store_commodity');

            // input harga/produksi
            Route::post('/', [PriceProductionController::class, 'store'])
                ->name('prices_productions.store');
            Route::post('/bulk', [PriceProductionController::class, 'bulkStore'])
                ->name('prices_productions.bulk');

            Route::get('/commodities/all', [PriceProductionController::class, 'getAllCommodities'])
                ->name('prices_productions.all_commodities');
        });

        // Manage User
        Route::name('manage.user.')->prefix('manage/user')->group(function () {
            Route::get('/', [ManageUserController::class, 'index'])->name('index');
            Route::get('/create', [ManageUserController::class, 'create'])->name('create');
            Route::post('/store', [ManageUserController::class, 'store'])->name('store');
            Route::put('/{id}/update-role', [ManageUserController::class, 'updateRoleUser'])->name('updateRole');
            Route::put('/{id}/update-tim', [ManageUserController::class, 'updateTimUser'])->name('updateTim');
            Route::get('/edit/{id}', [ManageUserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ManageUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [ManageUserController::class, 'destroy'])->name('destroy');
        });
    });
});
