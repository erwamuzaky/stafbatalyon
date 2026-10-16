<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePengaturanRequest;
use App\Models\Pengaturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    /**
     * Tampilkan formulir pengaturan halaman depan.
     */
    public function index(Request $request): View
    {
        return view('admin.pengaturan.index', [
            'pengaturan' => Pengaturan::peta(),
            'heroBackground' => Pengaturan::gambar('hero_background'),
            'bagianBackground' => Pengaturan::gambar('bagian_background'),
            'strukturLengkapBackground' => Pengaturan::gambar('struktur_lengkap_background'),
        ]);
    }

    /**
     * Simpan pembaruan pengaturan halaman depan.
     */
    public function update(UpdatePengaturanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Kunci yang ditangani khusus (bukan sekadar teks): gambar latar + checkbox hapusnya.
        $kunciKhusus = array_merge(
            array_keys(Pengaturan::LATAR_BERANDA),
            array_values(Pengaturan::LATAR_BERANDA),
        );

        // Sisanya: kunci teks pengaturan.
        $data = [];
        foreach ($validated as $kunci => $nilai) {
            if (! in_array($kunci, $kunciKhusus, true)) {
                $data[$kunci] = $nilai;
            }
        }

        $lama = Pengaturan::peta();

        foreach (Pengaturan::LATAR_BERANDA as $kunci => $flagHapus) {
            $pathLama = $lama[$kunci] ?? '';

            if ($request->boolean($flagHapus)) {
                // Hapus dimenangkan: buang file lama. Kalau user sekalian mengunggah
                // file baru, file itu tidak dipakai sehingga tidak perlu disimpan.
                if ($pathLama !== '') {
                    Storage::disk('public')->delete($pathLama);
                }
                $data[$kunci] = '';
            } elseif ($request->hasFile($kunci)) {
                // Ada unggahan baru: hapus file lama, lalu simpan yang baru.
                if ($pathLama !== '') {
                    Storage::disk('public')->delete($pathLama);
                }
                $data[$kunci] = $request->file($kunci)->store('pengaturan', 'public');
            }
        }

        Pengaturan::simpan($data);

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan halaman depan sudah disimpan.');
    }
}
