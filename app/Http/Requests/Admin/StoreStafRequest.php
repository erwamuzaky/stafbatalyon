<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreStafRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh menambah staf.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk menambah staf baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'bidang_id' => ['required', 'exists:bidangs,id'],
            'kelas' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'is_inti' => ['nullable', 'boolean'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:999'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'name.required' => 'Nama staf wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang sah.',
            'email.unique' => 'Email itu sudah dipakai akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'bidang_id.required' => 'Bidang wajib dipilih.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak sah.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.max' => 'Kelas terlalu panjang, maksimal 255 karakter.',
            'urutan.integer' => 'Urutan tampil harus berupa angka.',
            'urutan.min' => 'Urutan tampil minimal 1.',
            'urutan.max' => 'Urutan tampil maksimal 999.',
            'no_hp.max' => 'Nomor HP terlalu panjang, maksimal 20 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
