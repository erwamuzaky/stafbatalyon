<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PiketGerbang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Ringkasan singkat untuk admin.
     */
    public function index(): View
    {
        $mulaiMinggu = now()->startOfWeek();

        return view('admin.dashboard', [
            'jumlahStaf' => User::where('role', 'staf')->where('status', 'approved')->count(),
            'jumlahPending' => User::where('status', 'pending')->count(),
            'jumlahEvent' => Event::count(),
            'jumlahPiketMingguIni' => PiketGerbang::whereBetween('tanggal', [
                $mulaiMinggu->copy()->startOfDay(),
                $mulaiMinggu->copy()->addDays(5)->endOfDay(),
            ])->count(),
            'sebaranKelas' => StafProfile::query()
                ->whereHas('user', fn ($query) => $query->where('role', 'staf')->where('status', 'approved'))
                ->whereNotNull('kelas')
                ->where('kelas', '!=', '')
                ->selectRaw('kelas, count(*) as jumlah')
                ->groupBy('kelas')
                ->orderByDesc('jumlah')
                ->orderBy('kelas')
                ->get()
                ->pluck('jumlah', 'kelas'),
            'pendaftarTerbaru' => User::where('status', 'pending')
                ->with('stafProfile.bidang')
                ->latest()
                ->limit(5)
                ->get(),
            'piketHariIni' => PiketGerbang::whereDate('tanggal', today())
                ->with('user')
                ->orderBy('sesi')
                ->get(),
        ]);
    }
}
