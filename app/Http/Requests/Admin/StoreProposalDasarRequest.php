<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProposalDasarRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh membuat proposal dari proposal lama.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk membuat proposal berdasarkan proposal lama.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'judul' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
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
            'event_id.required' => 'Kegiatan tujuan wajib dipilih.',
            'event_id.exists' => 'Kegiatan tujuan tidak ditemukan.',
            'judul.required' => 'Judul proposal wajib diisi.',
            'judul.max' => 'Judul proposal terlalu panjang, maksimal 255 karakter.',
        ];
    }
}
