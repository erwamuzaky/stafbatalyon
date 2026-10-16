<?php

namespace App\Http\Requests\Admin;

use App\Models\Pengaturan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePengaturanRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah pengaturan halaman depan.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk semua kunci pengaturan.
     */
    public function rules(): array
    {
        // Kunci teks pendek (max 255 karakter)
        $shortKeys = [
            'situs_nama',
            'situs_sekolah',
            'hero_label',
            'tentang_label',
            'tentang_judul',
            'tentang_fitur1_judul',
            'tentang_fitur2_judul',
            'tentang_fitur3_judul',
            'struktur_judul',
            'bidang_judul',
            'ajakan_judul',
            'footer_nama',
            'footer_sekolah',
            'footer_peta_kata_kunci',
            'footer_peta_catatan',
            'footer_hak_cipta',
        ];

        // Kunci teks panjang (max 2000 karakter)
        $longKeys = [
            'hero_pengantar',
            'tentang_pengantar',
            'tentang_fitur1_teks',
            'tentang_fitur2_teks',
            'tentang_fitur3_teks',
            'struktur_subjudul',
            'bidang_subjudul',
            'ajakan_teks',
        ];

        $rules = [];

        // Short keys rules
        foreach ($shortKeys as $key) {
            $rules[$key] = ['nullable', 'string', 'max:255'];
        }

        // Long keys rules
        foreach ($longKeys as $key) {
            $rules[$key] = ['nullable', 'string', 'max:2000'];
        }

        // Aturan gambar latar beranda (hero, Struktur Staf, dan Bidang).
        foreach (array_keys(Pengaturan::LATAR_BERANDA) as $gambar) {
            $rules[$gambar] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        }

        // Aturan checkbox penghapus latar.
        foreach (array_values(Pengaturan::LATAR_BERANDA) as $hapus) {
            $rules[$hapus] = ['nullable', 'boolean'];
        }

        // Tingkat kegelapan lapisan gelap foto latar. Dibolehkan kosong supaya
        // kunci lama yang tidak dikirim form tes lama tetap aman; nilai yang
        // dikirim wajib salah satu dari pilihan (samar, sedang, tegas).
        $rules['latar_kepekatan'] = ['nullable', 'string', Rule::in(array_keys(Pengaturan::LATAR_KEPEKATAN))];

        return $rules;
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        $pesan = [];

        // Pesan error untuk tiap gambar latar beranda.
        foreach (array_keys(Pengaturan::LATAR_BERANDA) as $gambar) {
            $pesan[$gambar.'.image'] = 'Background harus berupa gambar.';
            $pesan[$gambar.'.mimes'] = 'Format gambar harus jpg, jpeg, png, atau webp.';
            $pesan[$gambar.'.max'] = 'Ukuran gambar maksimal 4 MB.';
        }

        // Pesan error tingkat kegelapan foto latar.
        $pesan['latar_kepekatan.in'] = 'Tingkat kegelapan latar harus salah satu dari: Samar, Sedang, atau Tegas.';

        return $pesan;
    }
}
