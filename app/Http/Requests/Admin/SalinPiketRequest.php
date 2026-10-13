<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SalinPiketRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh menyalin jadwal piket.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk menyalin jadwal satu minggu.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'minggu' => ['required', 'date'],
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
            'minggu.required' => 'Minggu yang mau disalin tidak dikenal.',
            'minggu.date' => 'Minggu yang mau disalin tidak sah.',
        ];
    }
}
