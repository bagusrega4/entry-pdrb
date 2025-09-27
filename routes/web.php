<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PriceProductionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\RasioController;
use App\Http\Controllers\IhpController;
use App\Http\Controllers\WipCbrController;

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

        // Input Harga dan Produksi
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
            Route::post('/indicators', [PriceProductionController::class, 'storeIndicator'])
                ->name('prices_productions.store_indicator');

            Route::get('/unit-harga', [PriceProductionController::class, 'getUnitHarga'])
                ->name('prices_productions.unit_harga');
            Route::post('/unit-harga', [PriceProductionController::class, 'storeUnitHarga'])
                ->name('prices_productions.store_unit_harga');

            Route::get('/unit-produksi', [PriceProductionController::class, 'getUnitProduksi'])
                ->name('prices_productions.unit_produksi');
            Route::post('/unit-produksi', [PriceProductionController::class, 'storeUnitProduksi'])
                ->name('prices_productions.store_unit_produksi');

            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])
                ->name('prices_productions.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])
                ->name('prices_productions.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])
                ->name('prices_productions.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])
                ->name('prices_productions.store_unit_perawatan');

            // Triwulan
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])
                ->name('prices_productions.triwulans');

            // tambah komoditas baru
            Route::post('/commodities', [PriceProductionController::class, 'storeCommodity'])
                ->name('prices_productions.store_commodity');

            // input harga/produksi
            Route::post('/', [PriceProductionController::class, 'store'])
                ->name('prices_productions.store');
            Route::post('/bulk', [PriceProductionController::class, 'bulkStore'])
                ->name('prices_productions.bulk');
            Route::post('/bulk-store', [PriceProductionController::class, 'bulkStore'])
                ->name('prices_productions.bulk_store');

            Route::get('/commodities/all', [PriceProductionController::class, 'getAllCommodities'])
                ->name('prices_productions.all_commodities');
        });

        // Input Rasio
        Route::prefix('rasio')->group(function () {
            Route::get('/', [RasioController::class, 'index'])
                ->name('rasio.index');

            // ambil subtree (anak-anak komoditas)
            Route::get('/commodities/{commodity}/subtree', [RasioController::class, 'getSubtree'])
                ->name('rasio.subtree');

            // generate kode otomatis untuk child baru
            Route::get('/commodities/{parent}/next-code', [RasioController::class, 'generateNextCode'])
                ->name('rasio.next_code');

            // indikator & satuan
            Route::get('/indicators', [RasioController::class, 'getIndicators'])
                ->name('rasio.indicators');
            Route::post('/indicators', [RasioController::class, 'storeIndicator'])
                ->name('rasio.store_indicator');

            Route::get('/unit-harga', [RasioController::class, 'getUnitHarga'])
                ->name('rasio.unit_harga');
            Route::post('/unit-harga', [RasioController::class, 'storeUnitHarga'])
                ->name('rasio.store_unit_harga');

            Route::get('/unit-produksi', [RasioController::class, 'getUnitProduksi'])
                ->name('rasio.unit_produksi');
            Route::post('/unit-produksi', [RasioController::class, 'storeUnitProduksi'])
                ->name('rasio.store_unit_produksi');

            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])
                ->name('rasio.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])
                ->name('rasio.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])
                ->name('rasio.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])
                ->name('rasio.store_unit_perawatan');

            // Triwulan
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])
                ->name('rasio.triwulans');

            // tambah komoditas baru
            Route::post('/commodities', [RasioController::class, 'storeCommodity'])
                ->name('rasio.store_commodity');

            // input harga/produksi
            Route::post('/', [RasioController::class, 'store'])
                ->name('rasio.store');
            Route::post('/bulk', [RasioController::class, 'bulkStore'])
                ->name('rasio.bulk');
            Route::post('/bulk-store', [RasioController::class, 'bulkStore'])
                ->name('rasio.bulk_store');

            Route::get('/commodities/all', [RasioController::class, 'getAllCommodities'])
                ->name('rasio.all_commodities');
        });

        // Input IHP
        Route::prefix('ihp')->group(function () {
            Route::get('/', [IhpController::class, 'index'])
                ->name('ihp.index');

            // ambil subtree (anak-anak komoditas)
            Route::get('/commodities/{commodity}/subtree', [IhpController::class, 'getSubtree'])
                ->name('ihp.subtree');

            // generate kode otomatis untuk child baru
            Route::get('/commodities/{parent}/next-code', [IhpController::class, 'generateNextCode'])
                ->name('ihp.next_code');

            // indikator & satuan
            Route::get('/indicators', [IhpController::class, 'getIndicators'])
                ->name('ihp.indicators');
            Route::post('/indicators', [IhpController::class, 'storeIndicator'])
                ->name('ihp.store_indicator');

            Route::get('/unit-harga', [IhpController::class, 'getUnitHarga'])
                ->name('ihp.unit_harga');
            Route::post('/unit-harga', [IhpController::class, 'storeUnitHarga'])
                ->name('ihp.store_unit_harga');

            Route::get('/unit-produksi', [IhpController::class, 'getUnitProduksi'])
                ->name('ihp.unit_produksi');
            Route::post('/unit-produksi', [IhpController::class, 'storeUnitProduksi'])
                ->name('ihp.store_unit_produksi');

            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])
                ->name('ihp.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])
                ->name('ihp.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])
                ->name('ihp.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])
                ->name('ihp.store_unit_perawatan');

            // Triwulan
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])
                ->name('ihp.triwulans');

            // tambah komoditas baru
            Route::post('/commodities', [IhpController::class, 'storeCommodity'])
                ->name('ihp.store_commodity');

            // input harga/produksi
            Route::post('/', [IhpController::class, 'store'])
                ->name('ihp.store');
            Route::post('/bulk', [IhpController::class, 'bulkStore'])
                ->name('ihp.bulk');
            Route::post('/bulk-store', [IhpController::class, 'bulkStore'])
                ->name('ihp.bulk_store');

            Route::get('/commodities/all', [IhpController::class, 'getAllCommodities'])
                ->name('ihp.all_commodities');
        });

        // Input WIP/CBR
        Route::prefix('wip-cbr')->group(function () {
            Route::get('/', [WipCbrController::class, 'index'])
                ->name('wip-cbr.index');

            // ambil subtree (anak-anak komoditas)
            Route::get('/commodities/{commodity}/subtree', [WipCbrController::class, 'getSubtree'])
                ->name('wip-cbr.subtree');

            // generate kode otomatis untuk child baru
            Route::get('/commodities/{parent}/next-code', [WipCbrController::class, 'generateNextCode'])
                ->name('wip-cbr.next_code');

            // generate kode untuk root baru (tanpa parent)
            Route::get('/commodities/next-code', [WipCbrController::class, 'generateNextCode'])
                ->name('wip-cbr.next_code_root');

            // indikator & satuan
            Route::get('/indicators', [WipCbrController::class, 'getIndicators'])
                ->name('wip-cbr.indicators');
            Route::post('/indicators', [WipCbrController::class, 'storeIndicator'])
                ->name('wip-cbr.store_indicator');

            Route::get('/unit-harga', [WipCbrController::class, 'getUnitHarga'])
                ->name('wip-cbr.unit_harga');
            Route::post('/unit-harga', [WipCbrController::class, 'storeUnitHarga'])
                ->name('wip-cbr.store_unit_harga');

            Route::get('/unit-produksi', [WipCbrController::class, 'getUnitProduksi'])
                ->name('wip-cbr.unit_produksi');
            Route::post('/unit-produksi', [WipCbrController::class, 'storeUnitProduksi'])
                ->name('wip-cbr.store_unit_produksi');

            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])
                ->name('wip-cbr.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])
                ->name('wip-cbr.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])
                ->name('wip-cbr.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])
                ->name('wip-cbr.store_unit_perawatan');

            // Triwulan
            Route::get('/triwulans', [WipCbrController::class, 'getTriwulans'])
                ->name('wip-cbr.triwulans');

            // tambah komoditas baru
            Route::post('/commodities', [WipCbrController::class, 'storeCommodity'])
                ->name('wip-cbr.store_commodity');

            // get all commodities untuk dropdown parent
            Route::get('/commodities/all', [WipCbrController::class, 'getAllCommodities'])
                ->name('wip-cbr.all_commodities');

            // input WIP-CBR data
            Route::post('/', [WipCbrController::class, 'store'])
                ->name('wip-cbr.store');
            Route::post('/bulk', [WipCbrController::class, 'bulkStore'])
                ->name('wip-cbr.bulk');
            Route::post('/bulk-store', [WipCbrController::class, 'bulkStore'])
                ->name('wip-cbr.bulk_store');
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
