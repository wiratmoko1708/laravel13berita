<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ---------- Halaman publik (tanpa login) ----------
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/berita/{slug}', [PublicController::class, 'show'])->name('berita.show');
Route::get('/kategori/{slug}', [PublicController::class, 'kategori'])->name('kategori.show');

// ---------- Dashboard (harus login) ----------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ---------- Berita: admin dan penulis ----------
Route::middleware(['auth', 'role:admin,penulis'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // parameter() memastikan nama parameter tetap {berita} (bukan {beritum}),
        // agar cocok dengan route model binding Post $berita di controller.
        Route::resource('berita', PostController::class)
            ->except('show')
            ->parameters(['berita' => 'berita']);
    });

// ---------- Kategori dan pengguna: hanya admin ----------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('kategori', CategoryController::class)->except('show');

        // parameter() memastikan nama parameter tetap {user} (bukan {pengguna}),
        // agar cocok dengan route model binding User $user di controller.
        Route::resource('pengguna', UserController::class)
            ->except('show')
            ->parameters(['pengguna' => 'user']);
    });

// ---------- Profil (dari Breeze) ----------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute login, register, dan reset password dari Breeze
require __DIR__.'/auth.php';
