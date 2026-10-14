<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'kelas' => [
                Rule::requiredIf(fn () => $this->user()?->stafProfile !== null),
                'nullable',
                'string',
                'max:255',
            ],
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
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama terlalu panjang, maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak sah.',
            'email.max' => 'Email terlalu panjang, maksimal 255 karakter.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.max' => 'Kelas terlalu panjang, maksimal 255 karakter.',
            'no_hp.max' => 'Nomor HP terlalu panjang, maksimal 20 karakter.',
        ];
    }
}
