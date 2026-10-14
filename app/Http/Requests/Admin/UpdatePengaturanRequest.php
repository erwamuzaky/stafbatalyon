<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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

        // Aturan gambar hero background
        $rules['hero_background'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

        // Aturan checkbox hapus background
        $rules['hapus_background'] = ['nullable', 'boolean'];

        return $rules;
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            // Pesan error gambar
            'hero_background.image' => 'Background harus berupa gambar.',
            'hero_background.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'hero_background.max' => 'Ukuran gambar maksimal 4 MB.',
        ];
    }
}
