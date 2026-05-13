<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PriceProductionController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\RasioController;
use App\Http\Controllers\IhpController;
use App\Http\Controllers\WipCbrController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\IOController;
use App\Http\Controllers\PdrbController;
use App\Http\Controllers\FinalisasiPdrbController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardExportController;
use App\Http\Controllers\SimulasiController;


Route::get('/', function () {
    return view('auth.login');
})->name('login');

require __DIR__ . '/auth.php';

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User is authenticated: ' . Auth::user()->name;
    } else {
        return 'User is not authenticated.';
    }
})->name('check-auth');

Route::get('/notfound', function () {
    return view('error.unauthorized');
})->name('error.unauthorized');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/edit-profile', [ProfileController::class, 'setPhotoProfile'])->name('edit.profile');
    Route::put('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');

    Route::middleware('role:1,2')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/export-pdf', [DashboardExportController::class, 'exportPdf'])->name('export-pdf');
    });

    Route::middleware('role:1,2')->prefix('simulasi')->name('simulasi.')->group(function () {
        Route::get('/',         [SimulasiController::class, 'index'])->name('index');
        Route::post('/proses',  [SimulasiController::class, 'proses'])->name('proses');
        Route::get('/reset',    [SimulasiController::class, 'reset'])->name('reset');

        Route::get('/skenario',         [SimulasiController::class, 'skenario'])->name('skenario');
        Route::post('/skenario/proses', [SimulasiController::class, 'prosesSkenario'])->name('proses-skenario');
        Route::get('/skenario/reset',   [SimulasiController::class, 'resetSkenario'])->name('reset-skenario');
        Route::get('/skenario/export/pdf', [SimulasiController::class, 'exportPdfSkenario'])->name('skenario.export.pdf');
        
        Route::get('/riwayat',          [SimulasiController::class, 'riwayat'])->name('riwayat');
        Route::post('/simpan',          [SimulasiController::class, 'simpan'])->name('simpan');
        Route::get('/riwayat/{id}',     [SimulasiController::class, 'lihatRiwayat'])->name('lihat-riwayat');
        Route::delete('/hapus/{id}',    [SimulasiController::class, 'hapus'])->name('hapus');

        Route::get('/export/excel', [SimulasiController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf',   [SimulasiController::class, 'exportPdf'])->name('export.pdf');

        Route::get('/chart/data',   [SimulasiController::class, 'chartData'])->name('chart.data');
        Route::get('/sektor/list',  [SimulasiController::class, 'listSektor'])->name('sektor.list');
        Route::get('/sektor/{id}',  [SimulasiController::class, 'detailSektor'])->name('sektor.detail');

        Route::get('/riwayat/{id}/export/excel', [SimulasiController::class, 'exportExcelRiwayat'])->name('riwayat.export.excel');
        Route::get('/riwayat/{id}/export/pdf',   [SimulasiController::class, 'exportPdfRiwayat'])->name('riwayat.export.pdf');
    });

    // Role 2 only: semua fitur lainnya
    Route::middleware('role:2')->group(function () {
        
        Route::prefix('prices-productions')->group(function () {
            Route::get('/', [PriceProductionController::class, 'index'])->name('prices_productions.index');
            Route::get('/commodities/{commodity}/subtree', [PriceProductionController::class, 'getSubtree'])->name('prices_productions.subtree');
            Route::get('/commodities/{parent}/next-code', [PriceProductionController::class, 'generateNextCode'])->name('prices_productions.next_code');
            Route::get('/indicators', [PriceProductionController::class, 'getIndicators'])->name('prices_productions.indicators');
            Route::post('/indicators', [PriceProductionController::class, 'storeIndicator'])->name('prices_productions.store_indicator');
            Route::get('/unit-harga', [PriceProductionController::class, 'getUnitHarga'])->name('prices_productions.unit_harga');
            Route::post('/unit-harga', [PriceProductionController::class, 'storeUnitHarga'])->name('prices_productions.store_unit_harga');
            Route::get('/unit-produksi', [PriceProductionController::class, 'getUnitProduksi'])->name('prices_productions.unit_produksi');
            Route::post('/unit-produksi', [PriceProductionController::class, 'storeUnitProduksi'])->name('prices_productions.store_unit_produksi');
            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])->name('prices_productions.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])->name('prices_productions.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])->name('prices_productions.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])->name('prices_productions.store_unit_perawatan');
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])->name('prices_productions.triwulans');
            Route::post('/commodities', [PriceProductionController::class, 'storeCommodity'])->name('prices_productions.store_commodity');
            Route::post('/', [PriceProductionController::class, 'store'])->name('prices_productions.store');
            Route::post('/bulk', [PriceProductionController::class, 'bulkStore'])->name('prices_productions.bulk');
            Route::post('/bulk-store', [PriceProductionController::class, 'bulkStore'])->name('prices_productions.bulk_store');
            Route::get('/commodities/all', [PriceProductionController::class, 'getAllCommodities'])->name('prices_productions.all_commodities');
            Route::get('/template', [PriceProductionController::class, 'downloadTemplate'])->name('prices_productions.template');
            Route::post('/import', [PriceProductionController::class, 'importExcel'])->name('prices_productions.import');
        });

        Route::prefix('rasio')->group(function () {
            Route::get('/', [RasioController::class, 'index'])->name('rasio.index');
            Route::get('/commodities/{commodity}/subtree', [RasioController::class, 'getSubtree'])->name('rasio.subtree');
            Route::get('/commodities/{parent}/next-code', [RasioController::class, 'generateNextCode'])->name('rasio.next_code');
            Route::get('/indicators', [RasioController::class, 'getIndicators'])->name('rasio.indicators');
            Route::post('/indicators', [RasioController::class, 'storeIndicator'])->name('rasio.store_indicator');
            Route::get('/unit-harga', [RasioController::class, 'getUnitHarga'])->name('rasio.unit_harga');
            Route::post('/unit-harga', [RasioController::class, 'storeUnitHarga'])->name('rasio.store_unit_harga');
            Route::get('/unit-produksi', [RasioController::class, 'getUnitProduksi'])->name('rasio.unit_produksi');
            Route::post('/unit-produksi', [RasioController::class, 'storeUnitProduksi'])->name('rasio.store_unit_produksi');
            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])->name('rasio.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])->name('rasio.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])->name('rasio.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])->name('rasio.store_unit_perawatan');
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])->name('rasio.triwulans');
            Route::post('/commodities', [RasioController::class, 'storeCommodity'])->name('rasio.store_commodity');
            Route::post('/', [RasioController::class, 'store'])->name('rasio.store');
            Route::post('/bulk', [RasioController::class, 'bulkStore'])->name('rasio.bulk');
            Route::post('/bulk-store', [RasioController::class, 'bulkStore'])->name('rasio.bulk_store');
            Route::get('/commodities/all', [RasioController::class, 'getAllCommodities'])->name('rasio.all_commodities');
            Route::get('/template', [RasioController::class, 'downloadTemplate'])->name('rasio.template');
            Route::post('/import', [RasioController::class, 'importExcel'])->name('rasio.import');
        });

        Route::prefix('ihp')->group(function () {
            Route::get('/', [IhpController::class, 'index'])->name('ihp.index');
            Route::get('/commodities/{commodity}/subtree', [IhpController::class, 'getSubtree'])->name('ihp.subtree');
            Route::get('/commodities/{parent}/next-code', [IhpController::class, 'generateNextCode'])->name('ihp.next_code');
            Route::get('/indicators', [IhpController::class, 'getIndicators'])->name('ihp.indicators');
            Route::post('/indicators', [IhpController::class, 'storeIndicator'])->name('ihp.store_indicator');
            Route::get('/unit-harga', [IhpController::class, 'getUnitHarga'])->name('ihp.unit_harga');
            Route::post('/unit-harga', [IhpController::class, 'storeUnitHarga'])->name('ihp.store_unit_harga');
            Route::get('/unit-produksi', [IhpController::class, 'getUnitProduksi'])->name('ihp.unit_produksi');
            Route::post('/unit-produksi', [IhpController::class, 'storeUnitProduksi'])->name('ihp.store_unit_produksi');
            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])->name('ihp.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])->name('ihp.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])->name('ihp.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])->name('ihp.store_unit_perawatan');
            Route::get('/triwulans', [PriceProductionController::class, 'getTriwulans'])->name('ihp.triwulans');
            Route::post('/commodities', [IhpController::class, 'storeCommodity'])->name('ihp.store_commodity');
            Route::post('/', [IhpController::class, 'store'])->name('ihp.store');
            Route::post('/bulk', [IhpController::class, 'bulkStore'])->name('ihp.bulk');
            Route::post('/bulk-store', [IhpController::class, 'bulkStore'])->name('ihp.bulk_store');
            Route::get('/commodities/all', [IhpController::class, 'getAllCommodities'])->name('ihp.all_commodities');
            Route::get('/template', [IhpController::class, 'downloadTemplate'])->name('ihp.template');
            Route::post('/import', [IhpController::class, 'importExcel'])->name('ihp.import');
        });

        Route::prefix('wip-cbr')->group(function () {
            Route::get('/', [WipCbrController::class, 'index'])->name('wip-cbr.index');
            Route::get('/commodities/{commodity}/subtree', [WipCbrController::class, 'getSubtree'])->name('wip-cbr.subtree');
            Route::get('/commodities/{parent}/next-code', [WipCbrController::class, 'generateNextCode'])->name('wip-cbr.next_code');
            Route::get('/commodities/next-code', [WipCbrController::class, 'generateNextCode'])->name('wip-cbr.next_code_root');
            Route::get('/indicators', [WipCbrController::class, 'getIndicators'])->name('wip-cbr.indicators');
            Route::post('/indicators', [WipCbrController::class, 'storeIndicator'])->name('wip-cbr.store_indicator');
            Route::get('/unit-harga', [WipCbrController::class, 'getUnitHarga'])->name('wip-cbr.unit_harga');
            Route::post('/unit-harga', [WipCbrController::class, 'storeUnitHarga'])->name('wip-cbr.store_unit_harga');
            Route::get('/unit-produksi', [WipCbrController::class, 'getUnitProduksi'])->name('wip-cbr.unit_produksi');
            Route::post('/unit-produksi', [WipCbrController::class, 'storeUnitProduksi'])->name('wip-cbr.store_unit_produksi');
            Route::get('/unit-luas', [WipCbrController::class, 'getUnitLuas'])->name('wip-cbr.unit_luas');
            Route::post('/unit-luas', [WipCbrController::class, 'storeUnitLuas'])->name('wip-cbr.store_unit_luas');
            Route::get('/unit-perawatan', [WipCbrController::class, 'getUnitPerawatan'])->name('wip-cbr.unit_perawatan');
            Route::post('/unit-perawatan', [WipCbrController::class, 'storeUnitPerawatan'])->name('wip-cbr.store_unit_perawatan');
            Route::get('/triwulans', [WipCbrController::class, 'getTriwulans'])->name('wip-cbr.triwulans');
            Route::post('/commodities', [WipCbrController::class, 'storeCommodity'])->name('wip-cbr.store_commodity');
            Route::get('/commodities/all', [WipCbrController::class, 'getAllCommodities'])->name('wip-cbr.all_commodities');
            Route::post('/', [WipCbrController::class, 'store'])->name('wip-cbr.store');
            Route::post('/bulk', [WipCbrController::class, 'bulkStore'])->name('wip-cbr.bulk');
            Route::post('/bulk-store', [WipCbrController::class, 'bulkStore'])->name('wip-cbr.bulk_store');
            Route::get('/template', [WipCbrController::class, 'downloadTemplate'])->name('wip-cbr.template');
            Route::post('/import', [WipCbrController::class, 'importExcel'])->name('wip-cbr.import');
        });

        Route::name('manage.user.')->prefix('manage/user')->group(function () {
            Route::get('/', [ManageUserController::class, 'index'])->name('index');
            Route::put('/{id}/update-role', [ManageUserController::class, 'updateRoleUser'])->name('updateRole');
            Route::put('/{id}/update-tim', [ManageUserController::class, 'updateTimUser'])->name('updateTim');
            Route::get('/edit/{id}', [ManageUserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ManageUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [ManageUserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('download')->name('download.')->group(function () {
            Route::get('/', [DownloadController::class, 'index'])->name('index');
            Route::post('/generate-pdf', [DownloadController::class, 'generatePdf'])->name('generate-pdf');
            Route::post('/update-column-config', [DownloadController::class, 'updateColumnConfig'])->name('update-column-config');
            Route::get('/preview', [DownloadController::class, 'preview'])->name('preview');
        });

        Route::name('documents.')->prefix('documents')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::post('/upload', [DocumentController::class, 'store'])->name('store');
            Route::get('/download/{id}', [DocumentController::class, 'download'])->name('download');
            Route::delete('/{id}', [DocumentController::class, 'destroy'])->name('destroy');
            Route::put('/{id}', [DocumentController::class, 'update'])->name('update');
            Route::get('/view/{id}/{filename?}', [DocumentController::class, 'show'])->name('view');
        });

        Route::prefix('io')->name('io.')->group(function () {
            Route::get('/', [IOController::class, 'index'])->name('index');
            Route::get('/create', [IOController::class, 'create'])->name('create');
            Route::post('/store', [IOController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [IOController::class, 'edit'])->name('edit');
            Route::put('/{id}', [IOController::class, 'update'])->name('update');
            Route::delete('/{id}', [IOController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/matrix-a', [IOController::class, 'matrixA'])->name('matrixA');
            Route::get('/{id}/leontief', [IOController::class, 'leontief'])->name('leontief');
            Route::get('/{id}/simulasi', [IOController::class, 'simulasi'])->name('simulasi');
            Route::get('/{id}/input', [IOController::class, 'input'])->name('input');
            Route::post('/{id}/store-matrix', [IOController::class, 'storeMatrix'])->name('storeMatrix');
        });

        Route::prefix('pdrb')->name('pdrb.')->group(function () {
            Route::get('/', [PdrbController::class, 'index'])->name('index');
            Route::post('/hitung', [PdrbController::class, 'hitung'])->name('hitung');
            Route::get('/hasil', [PdrbController::class, 'hasil'])->name('hasil');
            Route::delete('/reset', [PdrbController::class, 'reset'])->name('reset');
        });

        Route::prefix('finalisasi')->name('finalisasi.')->group(function () {
            Route::get('/', [FinalisasiPdrbController::class, 'index'])->name('index');
            Route::post('/proses', [FinalisasiPdrbController::class, 'finalisasi'])->name('proses');
            Route::post('/batal', [FinalisasiPdrbController::class, 'batal'])->name('batal');
            Route::get('/detail/{tahun}', [FinalisasiPdrbController::class, 'detail'])->name('detail');
            Route::get('/detail-versi', [FinalisasiPdrbController::class, 'detailVersi'])->name('detail-versi');
        });
    });
});