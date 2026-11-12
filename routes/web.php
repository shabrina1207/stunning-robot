<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminUserController;

// 1. Redirect halaman utama langsung ke /order
Route::get('/', function () {
    return redirect('/order');
});

// 2. Route tampilan halaman utama pemesanan
Route::get('/order', [OrderController::class, 'index'])->name('order.index');

// 3. Route untuk memproses dan menyimpan transaksi pesanan
Route::post('/order', [OrderController::class, 'store'])->middleware('throttle:orders');

// 4. Route tampilan struk / nota sukses setelah pembayaran
Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');


// ================= ROUTES KASIR =================

// Route Login Kasir (Hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.process');
});

// Route Logout & Halaman Dashboard Kasir (Hanya bisa diakses jika sudah login)
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Target redirect untuk middleware guest, agar staf yang sudah login
    // diarahkan ke dashboard dan tidak kembali ke halaman pemesanan.
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
    Route::get('/admin/orders', [KasirController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/rekap', [KasirController::class, 'rekap'])->name('admin.rekap');
    Route::get('/admin/rekap/harian', [KasirController::class, 'rekapHarian'])->name('admin.rekap.harian');
    Route::get('/admin/rekap/bulanan', [KasirController::class, 'rekapBulanan'])->name('admin.rekap.bulanan');
    Route::get('/admin/pembayaran', [KasirController::class, 'pembayaran'])->name('admin.pembayaran');
    Route::post('/admin/orders/{id}/verifikasi', [KasirController::class, 'verifikasi'])->name('admin.order.verifikasi');
    Route::post('/admin/orders/{id}/bayar-tunai', [KasirController::class, 'bayarTunai'])->name('admin.order.bayarTunai');
    Route::post('/admin/orders/{id}/status', [KasirController::class, 'updateStatus'])->name('admin.order.status');
    Route::get('/admin/orders/{id}/struk', [KasirController::class, 'struk'])->name('admin.order.struk');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/menu')->name('admin.menus.')->group(function () {
    Route::get('/', [AdminMenuController::class, 'index'])->name('index');
    Route::get('/create', [AdminMenuController::class, 'create'])->name('create');
    Route::post('/', [AdminMenuController::class, 'store'])->name('store');
    Route::get('/{menu}/edit', [AdminMenuController::class, 'edit'])->name('edit');
    Route::put('/{menu}', [AdminMenuController::class, 'update'])->name('update');
    Route::delete('/{menu}', [AdminMenuController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('index');
    Route::get('/create', [AdminUserController::class, 'create'])->name('create');
    Route::post('/', [AdminUserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
});
