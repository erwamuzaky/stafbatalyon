<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staf\DashboardController as StafDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// /dashboard bawaan Breeze diarahkan ke dashboard sesuai role.
Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'staf.dashboard');
})->middleware(['auth', 'approved'])->name('dashboard');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Area admin: hanya admin dengan status approved.
Route::middleware(['auth', 'approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('index');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

// Area staf: hanya staf dengan status approved.
Route::middleware(['auth', 'approved', 'role:staf'])->prefix('staf')->name('staf.')->group(function () {
    Route::get('/', fn () => redirect()->route('staf.dashboard'))->name('index');
    Route::get('/dashboard', [StafDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
