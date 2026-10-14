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
        $pengaturan = Pengaturan::peta();
        $heroBackground = Pengaturan::gambar('hero_background');

        return view('admin.pengaturan.index', compact('pengaturan', 'heroBackground'));
    }

    /**
     * Simpan pembaruan pengaturan halaman depan.
     */
    public function update(UpdatePengaturanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Kumpulkan hanya kunci teks (semua kecuali hero_background dan hapus_background)
        $data = [];
        foreach ($validated as $kunci => $nilai) {
            if (! in_array($kunci, ['hero_background', 'hapus_background'])) {
                $data[$kunci] = $nilai;
            }
        }

        $lama = Pengaturan::peta()['hero_background'] ?? '';

        if ($request->boolean('hapus_background')) {
            // Hapus dimenangkan: buang file lama. Kalau user sekalian mengunggah
            // file baru, file itu tidak dipakai sehingga tidak perlu disimpan.
            if ($lama !== '') {
                Storage::disk('public')->delete($lama);
            }
            $data['hero_background'] = '';
        } elseif ($request->hasFile('hero_background')) {
            // Ada unggahan baru: hapus file lama, lalu simpan yang baru.
            if ($lama !== '') {
                Storage::disk('public')->delete($lama);
            }
            $data['hero_background'] = $request->file('hero_background')->store('pengaturan', 'public');
        }

        // Simpan semua data pengaturan
        Pengaturan::simpan($data);

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan halaman depan sudah disimpan.');
    }
}
