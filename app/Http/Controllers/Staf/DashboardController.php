<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PiketGerbang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Berapa minggu jadwal piket yang ditampilkan, termasuk minggu ini.
     */
    private const JUMLAH_MINGGU = 4;

    /**
     * Berapa kegiatan terdekat yang ditampilkan.
     */
    private const JUMLAH_KEGIATAN = 5;

    /**
     * Dashboard staf: sapaan, jadwal piket sendiri, dan info kegiatan terdekat.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $profil = $user->stafProfile()->with('bidang')->first();

        $awalMingguIni = now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $akhirTampilan = $awalMingguIni->copy()
            ->addWeeks(self::JUMLAH_MINGGU - 1)
            ->endOfWeek(Carbon::SATURDAY)
            ->endOfDay();

        $jadwalPiket = PiketGerbang::query()
            ->where('user_id', $user->id)
            ->whereBetween('tanggal', [$awalMingguIni, $akhirTampilan])
            ->orderBy('tanggal')
            ->get()
            ->map(fn (PiketGerbang $piket): array => [
                'tanggal' => $piket->tanggal->locale('id'),
                'catatan' => $piket->catatan,
                'hariIni' => $piket->tanggal->isToday(),
                'mingguIni' => $piket->tanggal->betweenIncluded(
                    $awalMingguIni,
                    $awalMingguIni->copy()->endOfWeek(Carbon::SATURDAY),
                ),
            ]);

        return view('staf.dashboard', [
            'profil' => $profil,
            'jadwalPiket' => $jadwalPiket,
            'piketHariIni' => $jadwalPiket->firstWhere('hariIni', true),
            'kegiatan' => $this->kegiatanTerdekat(),
        ]);
    }

    /**
     * Kegiatan yang belum selesai, diurutkan dari tanggal mulai terdekat.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function kegiatanTerdekat(): Collection
    {
        return Event::query()
            ->whereIn('status', ['rencana', 'berjalan'])
            ->orderBy('tanggal_mulai')
            ->orderBy('nama')
            ->limit(self::JUMLAH_KEGIATAN)
            ->get()
            ->map(fn (Event $event): array => [
                'nama' => $event->nama,
                'tahun' => $event->tahun,
                'status' => $event->status,
                'statusLabel' => EventController::STATUS[$event->status] ?? $event->status,
                'tanggalMulai' => $event->tanggal_mulai?->locale('id'),
                'tanggalSelesai' => $event->tanggal_selesai?->locale('id'),
            ]);
    }
}
