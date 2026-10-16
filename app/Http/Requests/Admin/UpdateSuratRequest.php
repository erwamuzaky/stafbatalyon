<?php

namespace App\Http\Requests\Admin;

use App\Models\Surat;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah surat.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi isian surat.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $suratId = $this->route('surat')?->id;

        return [
            'jenis' => ['required', Rule::in(array_keys(Surat::JENIS))],
            'tanggal' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'nomor' => ['required', 'string', 'max:100', Rule::unique('surats', 'nomor')->ignore($suratId)],
            'tujuan' => ['nullable', 'string', 'max:255'],
            'isi' => ['nullable', 'string'],
            'periode_id' => ['nullable', 'integer', Rule::exists('periodes', 'id')],
            'event_id' => ['nullable', 'integer', Rule::exists('events', 'id')],
            'penandatangan' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'tempat' => ['required', 'string', 'max:100'],
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
            'jenis.required' => 'Jenis surat wajib dipilih.',
            'jenis.in' => 'Jenis surat yang dipilih tidak dikenal.',
            'tanggal.required' => 'Tanggal surat wajib diisi.',
            'tanggal.date' => 'Tanggal surat tidak sah.',
            'perihal.required' => 'Perihal wajib diisi.',
            'perihal.max' => 'Perihal terlalu panjang, maksimal 255 karakter.',
            'nomor.required' => 'Nomor surat wajib diisi.',
            'nomor.max' => 'Nomor surat terlalu panjang, maksimal 100 karakter.',
            'nomor.unique' => 'Nomor surat sudah dipakai.',
            'penandatangan.required' => 'Penanda tangan wajib diisi.',
            'jabatan.required' => 'Jabatan penanda tangan wajib diisi.',
            'tempat.required' => 'Tempat wajib diisi.',
            'periode_id.exists' => 'Periode yang dipilih tidak dikenal.',
            'event_id.exists' => 'Kegiatan yang dipilih tidak dikenal.',
        ];
    }
}
