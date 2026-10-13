<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengunggah proposal.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk mengunggah proposal baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
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
            'judul.required' => 'Judul proposal wajib diisi.',
            'judul.max' => 'Judul proposal terlalu panjang, maksimal 255 karakter.',
            'file.required' => 'Berkas proposal wajib diunggah.',
            'file.file' => 'Berkas proposal tidak sah.',
            'file.mimes' => 'Berkas harus berupa PDF, DOC, atau DOCX.',
            'file.max' => 'Ukuran berkas terlalu besar, maksimal 10 MB.',
        ];
    }
}
