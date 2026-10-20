<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SalinPiketRequest;
use App\Http\Requests\Admin\StorePiketRequest;
use App\Http\Requests\Admin\UpdatePiketRequest;
use App\Models\PiketGerbang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PiketController extends Controller
{
    /**
     * Sesi piket beserta labelnya.
     *
     * @var array<string, string>
     */
    public const SESI = PiketGerbang::SESI;

    /**
     * Jumlah hari yang ditampilkan dalam satu minggu (Senin sampai Sabtu).
     */
    private const JUMLAH_HARI = 6;

    /**
     * Jadwal piket satu minggu dalam bentuk tabel Senin-Sabtu x sesi.
     */
    public function index(Request $request): View
    {
        $minggu = $this->awalMinggu($request->query('minggu'));
        $akhir = $minggu->copy()->addDays(self::JUMLAH_HARI - 1);
        $mingguIni = now()->startOfWeek(Carbon::MONDAY);

        return view('admin.piket.index', [
            'minggu' => $minggu,
            'akhir' => $akhir,
            'hari' => $this->hariMinggu($minggu),
            'jadwal' => $this->jadwalMinggu($minggu, $akhir),
            'sesi' => self::SESI,
            'stafPilihan' => $this->stafPilihan(),
            'mingguIni' => $mingguIni->toDateString(),
            'mingguSebelumnya' => $minggu->copy()->subWeek()->toDateString(),
            'mingguBerikutnya' => $minggu->copy()->addWeek()->toDateString(),
        ]);
    }

    /**
     * Form tambah entri piket (bisa beberapa staf sekaligus).
     */
    public function create(Request $request): View
    {
        $tanggal = $request->query('tanggal');

        return view('admin.piket.create', [
            'stafPilihan' => $this->stafPilihan(),
            'sesi' => self::SESI,
            'tanggalDefault' => $this->tanggalSah($tanggal) ?? now()->toDateString(),
        ]);
    }

    /**
     * Simpan entri piket baru untuk satu tanggal dan sesi.
     */
    public function store(StorePiketRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tanggal = $request->date('tanggal')->toDateString();

        foreach ($data['user_ids'] as $userId) {
            PiketGerbang::create([
                'tanggal' => $tanggal,
                'sesi' => $data['sesi'],
                'user_id' => $userId,
                'catatan' => $data['catatan'] ?? null,
            ]);
        }

        $jumlah = count($data['user_ids']);

        return redirect()->route('admin.piket.index', ['minggu' => $tanggal])
            ->with('success', $jumlah.' staf sudah dijadwalkan piket pada '.$tanggal.'.');
    }

    /**
     * Form ubah satu entri piket.
     */
    public function edit(PiketGerbang $piket): View
    {
        return view('admin.piket.edit', [
            'piket' => $piket,
            'stafPilihan' => $this->stafPilihan(),
            'sesi' => self::SESI,
        ]);
    }

    /**
     * Simpan perubahan satu entri piket.
     */
    public function update(UpdatePiketRequest $request, PiketGerbang $piket): RedirectResponse
    {
        $piket->update($request->validated());

        return redirect()->route('admin.piket.index', [
            'minggu' => $piket->tanggal->toDateString(),
        ])->with('success', 'Jadwal piket '.$piket->user->name.' sudah diperbarui.');
    }

    /**
     * Hapus satu entri piket.
     */
    public function destroy(PiketGerbang $piket): RedirectResponse
    {
        $nama = $piket->user->name;
        $tanggal = $piket->tanggal->toDateString();

        $piket->delete();

        return redirect()->route('admin.piket.index', ['minggu' => $tanggal])
            ->with('success', 'Jadwal piket '.$nama.' sudah dihapus.');
    }

    /**
     * Salin seluruh jadwal satu minggu ke minggu berikutnya.
     *
     * Entri yang sudah ada di minggu tujuan dilewati supaya tidak ada duplikat.
     */
    public function salin(SalinPiketRequest $request): RedirectResponse
    {
        $sumber = $this->awalMinggu($request->input('minggu'));
        $akhirSumber = $sumber->copy()->addDays(self::JUMLAH_HARI - 1);
        $tujuan = $sumber->copy()->addWeek();

        $entri = PiketGerbang::whereBetween('tanggal', [
            $sumber->copy()->startOfDay(),
            $akhirSumber->copy()->endOfDay(),
        ])->get();

        $disalin = 0;
        $dilewati = 0;

        foreach ($entri as $item) {
            $tanggalBaru = $item->tanggal->copy()->addWeek()->toDateString();

            $sudahAda = PiketGerbang::whereDate('tanggal', $tanggalBaru)
                ->where('sesi', $item->sesi)
                ->where('user_id', $item->user_id)
                ->exists();

            if ($sudahAda) {
                $dilewati++;

                continue;
            }

            PiketGerbang::create([
                'tanggal' => $tanggalBaru,
                'sesi' => $item->sesi,
                'user_id' => $item->user_id,
                'catatan' => $item->catatan,
            ]);

            $disalin++;
        }

        $pesan = $disalin.' jadwal piket disalin ke minggu berikutnya.';

        if ($dilewati > 0) {
            $pesan .= ' '.$dilewati.' entri dilewati karena sudah ada.';
        }

        if ($disalin === 0 && $dilewati === 0) {
            $pesan = 'Belum ada jadwal pada minggu ini, jadi tidak ada yang disalin.';
        }

        return redirect()->route('admin.piket.index', [
            'minggu' => $tujuan->toDateString(),
        ])->with('success', $pesan);
    }

    /**
     * Tentukan hari Senin dari minggu yang diminta.
     *
     * Kalau nilai tidak sah atau kosong, pakai minggu berjalan.
     */
    private function awalMinggu(mixed $nilai): Carbon
    {
        $tanggal = $this->tanggalSah($nilai);

        if ($tanggal === null) {
            return now()->startOfWeek(Carbon::MONDAY);
        }

        return Carbon::parse($tanggal)->startOfWeek(Carbon::MONDAY);
    }

    /**
     * Kembalikan nilai tanggal (Y-m-d) kalau sah, atau null kalau tidak.
     */
    private function tanggalSah(mixed $nilai): ?string
    {
        if (! is_string($nilai) || trim($nilai) === '') {
            return null;
        }

        try {
            return Carbon::parse($nilai)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Daftar hari dalam satu minggu (Senin sampai Sabtu).
     *
     * @return array<int, array{tanggal: Carbon, label: string, tanggalLabel: string}>
     */
    private function hariMinggu(Carbon $minggu): array
    {
        $label = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari = [];

        for ($i = 0; $i < self::JUMLAH_HARI; $i++) {
            $tanggal = $minggu->copy()->addDays($i);

            $hari[] = [
                'tanggal' => $tanggal,
                'label' => $label[$i],
                'tanggalLabel' => $tanggal->format('d/m/Y'),
            ];
        }

        return $hari;
    }

    /**
     * Jadwal piket dikelompokkan per tanggal lalu per sesi.
     *
     * @return array<string, array<string, Collection<int, PiketGerbang>>>
     */
    private function jadwalMinggu(Carbon $minggu, Carbon $akhir): array
    {
        $entri = PiketGerbang::with('user')
            ->whereBetween('tanggal', [
                $minggu->copy()->startOfDay(),
                $akhir->copy()->endOfDay(),
            ])
            ->orderBy('tanggal')
            ->orderBy('sesi')
            ->get();

        $jadwal = [];

        foreach ($entri as $item) {
            $jadwal[$item->tanggal->toDateString()][$item->sesi][] = $item;
        }

        return $jadwal;
    }

    /**
     * Staf yang boleh dijadwalkan piket: role staf, sudah disetujui, dan ikut
     * piket gerbang. Staf yang belum punya profil tetap dianggap ikut.
     */
    private function stafPilihan(): Collection
    {
        return User::where('role', 'staf')
            ->where('status', 'approved')
            ->whereDoesntHave('stafProfile', fn ($query) => $query->where('ikut_piket', false))
            ->with('stafProfile.bidang')
            ->orderBy('name')
            ->get();
    }
}
