<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardKetuaController;
use App\Http\Controllers\DashboardAnggotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MonitoringOperatorController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\ManageUserController;

// -------------------------------------------------------------------
// Halaman Home
// -------------------------------------------------------------------
Route::get('/', function () {
    return view('home');
})->name('home');

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

    Route::middleware('role:1,2,3')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard'); // untuk admin (id_role = 3)

        Route::get('/dashboardKetua', [DashboardKetuaController::class, 'index'])
            ->name('dashboard.ketua'); // untuk ketua tim (id_role = 2)

        Route::get('/dashboardAnggota', [DashboardAnggotaController::class, 'index'])
            ->name('dashboard.anggota'); // untuk anggota (id_role = 1)

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/edit-profile', [ProfileController::class, 'setPhotoProfile'])->name('edit.profile');
        Route::put('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');

        // Form
        Route::name('form.')->prefix('/form')->group(function () {
            Route::get('/', [FormController::class, 'index'])->name('index');
            Route::post('/store', [FormController::class, 'store'])->name('store');
            Route::get('/create', [FormController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [FormController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [FormController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [FormController::class, 'destroy'])->name('delete');
            Route::get('/get-kegiatan/{tim_id}', [FormController::class, 'getKegiatan'])
                ->name('get.kegiatan');
        });

        // Jadwal
        Route::name('jadwal.')->prefix('/jadwal')->group(function () {
            Route::get('/create', [JadwalController::class, 'create'])->name('create');
            Route::post('/store', [JadwalController::class, 'store'])->name('store');
        });

        // Monitoring Operator
        Route::name('monitoring.operator.')->prefix('/monitoring/operator')->group(function () {
            // Menampilkan tabel monitoring
            Route::get('/', [MonitoringOperatorController::class, 'upload'])->name('index');

            // Upload bukti transfer (jika ada)
            Route::get('/upload/{id}', [MonitoringOperatorController::class, 'upload'])->name('upload');
            Route::post('/upload/{id}', [MonitoringOperatorController::class, 'store'])->name('store');

            // Ambil bukti transfer
            Route::get('/get-bukti-transfer/{id}', [MonitoringOperatorController::class, 'getBuktiTransfer'])->name('get.bukti.transfer');

            // Update status diketahui
            Route::put('/update-status/{id}', [MonitoringOperatorController::class, 'updateStatus'])->name('update.status');

            // link google drive
            Route::put('/update-link', [MonitoringOperatorController::class, 'updateLink'])->name('update.link');
        });

        // Manage Kegiatan
        Route::name('manage.kegiatan.')->prefix('manage/kegiatan')->group(function () {
            Route::get('/', [KegiatanController::class, 'index'])->name('index');
            Route::get('/create', [KegiatanController::class, 'create'])->name('create');
            Route::post('/store', [KegiatanController::class, 'store'])->name('store');
            Route::patch('/{id}/selesai', [KegiatanController::class, 'selesai'])->name('selesai');
            Route::patch('/{id}/aktif', [KegiatanController::class, 'aktif'])->name('aktif');
            Route::post('/import', [KegiatanController::class, 'import'])->name('import');
            Route::get('/template-excel', [KegiatanController::class, 'downloadTemplate'])->name('template');
            Route::get('/export/excel', [KegiatanController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [KegiatanController::class, 'exportPDF'])->name('export.pdf');
            Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('destroy');
        });

        // Panduan
        Route::prefix('panduan')->name('panduan.')->group(function () {
            Route::get('/', [PanduanController::class, 'index'])->name('index');
            Route::get('/upload', [PanduanController::class, 'uploadPanduan'])->name('upload.form');
            Route::post('/upload', [PanduanController::class, 'store'])->name('upload');
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
