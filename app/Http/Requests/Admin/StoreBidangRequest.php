<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBidangRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh menambah bidang.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk menambah bidang.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255', 'unique:bidangs,nama'],
            'tampil_di_beranda' => ['boolean'],
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
            'nama.required' => 'Nama bidang wajib diisi.',
            'nama.unique' => 'Nama bidang itu sudah ada. Pakai nama lain.',
            'nama.max' => 'Nama bidang terlalu panjang, maksimal 255 karakter.',
        ];
    }
}
