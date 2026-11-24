<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemSampahController;
use App\Http\Controllers\Admin\KategoriSampahController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\NasabahLookupController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\TransaksiSetorController;
use App\Http\Controllers\Admin\TransaksiTarikController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:admin')->prefix('kategori-sampah')->name('kategori.')->group(function () {
            Route::get('/', [KategoriSampahController::class, 'index'])->name('index');
            Route::post('/', [KategoriSampahController::class, 'store'])->name('store');
            Route::put('/{kategori}', [KategoriSampahController::class, 'update'])->name('update');
            Route::delete('/{kategori}', [KategoriSampahController::class, 'destroy'])->name('destroy');

            Route::prefix('{kategori}/item')->name('item.')->group(function () {
                Route::get('/', [ItemSampahController::class, 'index'])->name('index');
                Route::post('/', [ItemSampahController::class, 'store'])->name('store');
                Route::put('/{item}', [ItemSampahController::class, 'update'])->name('update');
                Route::delete('/{item}', [ItemSampahController::class, 'destroy'])->name('destroy');
            });
        });

        Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::middleware('role:admin,petugas')->prefix('nasabah')->name('nasabah.')->group(function () {
            Route::get('/', [NasabahLookupController::class, 'index'])->name('index');
            Route::get('/{nasabah}', [NasabahLookupController::class, 'show'])->name('show');
        });

        Route::middleware('role:admin,petugas')->prefix('setoran')->name('setoran.')->group(function () {
            Route::get('/', [TransaksiSetorController::class, 'index'])->name('index');
            Route::get('/create', [TransaksiSetorController::class, 'create'])->name('create');
            Route::post('/', [TransaksiSetorController::class, 'store'])->name('store');
            Route::get('/{setoran}/bukti', [TransaksiSetorController::class, 'bukti'])->name('bukti');
        });

        Route::middleware('role:admin,petugas')->prefix('penarikan')->name('penarikan.')->group(function () {
            Route::get('/', [TransaksiTarikController::class, 'index'])->name('index');
            Route::post('/{penarikan}/approve', [TransaksiTarikController::class, 'approve'])->name('approve');
            Route::post('/{penarikan}/reject', [TransaksiTarikController::class, 'reject'])->name('reject');
        });

        Route::middleware('role:admin')->prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/setoran', [LaporanController::class, 'setoran'])->name('setoran');
            Route::get('/setoran/export-csv', [LaporanController::class, 'setoranExportCsv'])->name('setoran.exportCsv');
            Route::get('/setoran/export-pdf', [LaporanController::class, 'setoranExportPdf'])->name('setoran.exportPdf');
            Route::get('/penarikan', [LaporanController::class, 'penarikan'])->name('penarikan');
            Route::get('/penarikan/export-csv', [LaporanController::class, 'penarikanExportCsv'])->name('penarikan.exportCsv');
            Route::get('/penarikan/export-pdf', [LaporanController::class, 'penarikanExportPdf'])->name('penarikan.exportPdf');
        });
    });
});
