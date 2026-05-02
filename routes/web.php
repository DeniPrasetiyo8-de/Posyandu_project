<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('index');
    Route::get('/informasi-anak', [App\Http\Controllers\DashboardController::class, 'informasiAnak'])->name('informasi.anak');
    Route::get('/informasi-ibu', [App\Http\Controllers\DashboardController::class, 'informasiIbu'])->name('informasi.ibu');
    Route::get('/kms', [App\Http\Controllers\DashboardController::class, 'kms'])->name('kms');
    Route::get('/kader', [App\Http\Controllers\DashboardController::class, 'kader'])->name('kader');
    Route::get('/artikel', [App\Http\Controllers\DashboardController::class, 'artikel'])->name('artikel');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [App\Http\Controllers\AdminController::class, 'jadwal'])->name('jadwal');
    Route::get('/informasi/anak', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.anak');
    Route::get('/informasi/ibu', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.ibu');
    Route::get('/kms', [App\Http\Controllers\AdminController::class, 'kmsAnalytics'])->name('kms');
    Route::get('/kader', [App\Http\Controllers\AdminController::class, 'kader'])->name('kader');
});

Route::middleware('auth')->group(function () {
    Route::resource('children', ChildController::class);
});

Route::get('/whatsapp-generator', function () {
    return view('whatsapp.generator');
});

// Duplicate children resource removed - auth middleware group sudah cover
