<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStafRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah data staf.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk mengubah data staf.
     * Nama dan email tidak diubah dari halaman ini.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bidang_id' => ['required', 'exists:bidangs,id'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'is_inti' => ['nullable', 'boolean'],
            'no_hp' => ['nullable', 'string', 'max:20'],
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
            'bidang_id.required' => 'Bidang wajib dipilih.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak sah.',
            'no_hp.max' => 'Nomor HP terlalu panjang, maksimal 20 karakter.',
        ];
    }
}
