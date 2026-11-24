<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriSampahController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransaksiSetorController;
use App\Http\Controllers\Api\TransaksiTarikController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

    Route::middleware(['auth:sanctum', 'role:nasabah'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/saldo', [ProfileController::class, 'saldo'])->name('saldo');

        Route::get('/kategori-sampah', [KategoriSampahController::class, 'index'])->name('kategori.index');

        Route::get('/setoran', [TransaksiSetorController::class, 'index'])->name('setoran.index');

        Route::get('/penarikan', [TransaksiTarikController::class, 'index'])->name('penarikan.index');
        Route::post('/penarikan', [TransaksiTarikController::class, 'store'])->name('penarikan.store');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });
});
