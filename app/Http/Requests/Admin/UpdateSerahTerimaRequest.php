<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSerahTerimaRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah serah terima.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk mengubah serah terima.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'periode_id' => ['required', 'integer', Rule::exists('periodes', 'id')],
            'periode_lama_id' => ['nullable', 'integer', Rule::exists('periodes', 'id'), 'different:periode_id'],
            'tanggal' => ['required', 'date'],
            'tempat' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'berkas' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:4096'],
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
            'periode_id.required' => 'Periode baru wajib dipilih.',
            'periode_id.exists' => 'Periode yang dipilih tidak ditemukan.',
            'periode_lama_id.exists' => 'Periode lama yang dipilih tidak ditemukan.',
            'periode_lama_id.different' => 'Periode lama tidak boleh sama dengan periode baru.',
            'tanggal.required' => 'Tanggal serah terima wajib diisi.',
            'tempat.max' => 'Tempat terlalu panjang, maksimal 255 karakter.',
            'catatan.max' => 'Catatan terlalu panjang, maksimal 2000 karakter.',
            'berkas.mimes' => 'Berkas harus berupa PDF, Word, atau gambar.',
            'berkas.max' => 'Ukuran berkas maksimal 4 MB.',
        ];
    }
}
