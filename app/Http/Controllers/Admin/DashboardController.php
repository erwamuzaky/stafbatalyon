<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PiketGerbang;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Ringkasan singkat untuk admin.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'jumlahStaf' => User::where('status', 'approved')->count(),
            'jumlahPending' => User::where('status', 'pending')->count(),
            'jumlahEvent' => Event::count(),
            'jumlahPiketMingguIni' => PiketGerbang::whereBetween('tanggal', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])->count(),
        ]);
    }
}
