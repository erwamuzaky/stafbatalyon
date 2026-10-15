<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilePhotoRequest extends FormRequest
{
    /**
     * Hanya user yang punya profil staf yang boleh memasang atau menghapus
     * fotonya sendiri. Admin tanpa profil staf otomatis ditolak.
     */
    public function authorize(): bool
    {
        return $this->user()?->stafProfile !== null;
    }

    /**
     * Aturan validasi pasang/ganti/hapus foto profil.
     *
     * Berkas wajib ada saat memasang (PATCH). Saat menghapus (DELETE) tidak
     * ada berkas yang dikirim, jadi aturannya cukup "nullable".
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'foto' => [
                Rule::requiredIf(fn () => $this->isMethod('patch')),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'foto.required' => 'Pilih foto yang ingin dipasang.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
