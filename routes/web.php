<?php

use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\BidangController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PiketController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\StafController;
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
});

// Area admin: hanya admin dengan status approved.
Route::middleware(['auth', 'approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('index');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola akun: persetujuan, penolakan, nonaktif, hapus, reset kata sandi.
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::patch('/akun/{user}/setujui', [AkunController::class, 'approve'])->name('akun.approve');
    Route::patch('/akun/{user}/tolak', [AkunController::class, 'reject'])->name('akun.reject');
    Route::patch('/akun/{user}/nonaktifkan', [AkunController::class, 'deactivate'])->name('akun.deactivate');
    Route::post('/akun/{user}/reset-password', [AkunController::class, 'resetPassword'])->name('akun.reset-password');
    Route::delete('/akun/{user}', [AkunController::class, 'destroy'])->name('akun.destroy');

    // Data staf.
    Route::get('/staf', [StafController::class, 'index'])->name('staf.index');
    Route::get('/staf/tambah', [StafController::class, 'create'])->name('staf.create');
    Route::post('/staf', [StafController::class, 'store'])->name('staf.store');
    Route::get('/staf/{user}/edit', [StafController::class, 'edit'])->name('staf.edit');
    Route::patch('/staf/{user}', [StafController::class, 'update'])->name('staf.update');
    Route::delete('/staf/{user}', [StafController::class, 'destroy'])->name('staf.destroy');

    // Bidang.
    Route::get('/bidang', [BidangController::class, 'index'])->name('bidang.index');
    Route::get('/bidang/tambah', [BidangController::class, 'create'])->name('bidang.create');
    Route::post('/bidang', [BidangController::class, 'store'])->name('bidang.store');
    Route::get('/bidang/{bidang}/edit', [BidangController::class, 'edit'])->name('bidang.edit');
    Route::patch('/bidang/{bidang}', [BidangController::class, 'update'])->name('bidang.update');
    Route::delete('/bidang/{bidang}', [BidangController::class, 'destroy'])->name('bidang.destroy');

    // Piket gerbang: pembagian staf per tanggal dan sesi.
    Route::get('/piket', [PiketController::class, 'index'])->name('piket.index');
    Route::get('/piket/tambah', [PiketController::class, 'create'])->name('piket.create');
    Route::post('/piket', [PiketController::class, 'store'])->name('piket.store');
    Route::post('/piket/salin', [PiketController::class, 'salin'])->name('piket.salin');
    Route::get('/piket/{piket}/edit', [PiketController::class, 'edit'])->name('piket.edit');
    Route::patch('/piket/{piket}', [PiketController::class, 'update'])->name('piket.update');
    Route::delete('/piket/{piket}', [PiketController::class, 'destroy'])->name('piket.destroy');

    // Kegiatan (event).
    Route::get('/event', [EventController::class, 'index'])->name('event.index');
    Route::get('/event/tambah', [EventController::class, 'create'])->name('event.create');
    Route::post('/event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::patch('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');

    // Proposal: unggah baru, pakai proposal lama sebagai dasar, hapus.
    Route::post('/event/{event}/proposal', [ProposalController::class, 'store'])->name('proposal.store');
    Route::get('/proposal/{proposal}/dasar', [ProposalController::class, 'createDari'])->name('proposal.dasar.create');
    Route::post('/proposal/{proposal}/dasar', [ProposalController::class, 'storeDari'])->name('proposal.dasar.store');
    Route::delete('/proposal/{proposal}', [ProposalController::class, 'destroy'])->name('proposal.destroy');

    // Arsip proposal dari semua kegiatan.
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/{proposal}/unduh', [ArsipController::class, 'unduh'])->name('arsip.unduh');
});

// Area staf: hanya staf dengan status approved.
Route::middleware(['auth', 'approved', 'role:staf'])->prefix('staf')->name('staf.')->group(function () {
    Route::get('/', fn () => redirect()->route('staf.dashboard'))->name('index');
    Route::get('/dashboard', [StafDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
