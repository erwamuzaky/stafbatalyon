<?php

namespace App\Http\Requests\Admin;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CetakAbsensiRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mencetak absensi.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi isian cetak absensi.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'filter' => ['required', Rule::in(array_keys(User::PESERTA_FILTER))],
            'bidang_id' => [
                'nullable',
                'integer',
                Rule::exists('bidangs', 'id'),
                Rule::requiredIf(fn (): bool => $this->input('filter') === 'bidang'),
            ],
            'penandatangan' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'baris_kosong' => ['nullable', 'integer', 'min:0', 'max:20'],
            'aksi' => ['nullable', Rule::in(['lihat', 'unduh'])],
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
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi atau dipilih dari daftar kegiatan.',
            'nama_kegiatan.max' => 'Nama kegiatan terlalu panjang, maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Tanggal kegiatan tidak sah.',
            'filter.required' => 'Penyaring peserta wajib dipilih.',
            'filter.in' => 'Penyaring peserta yang dipilih tidak dikenal.',
            'bidang_id.integer' => 'Bidang yang dipilih tidak sah.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak dikenal.',
            'bidang_id.required' => 'Pilih bidang yang akan dicetak absensinya.',
            'bidang_id.required_if' => 'Pilih bidang yang akan dicetak absensinya.',
            'penandatangan.max' => 'Nama penandatangan terlalu panjang, maksimal 255 karakter.',
            'jabatan.max' => 'Jabatan penandatangan terlalu panjang, maksimal 255 karakter.',
            'baris_kosong.integer' => 'Jumlah baris kosong harus berupa angka.',
            'baris_kosong.min' => 'Jumlah baris kosong tidak boleh kurang dari 0.',
            'baris_kosong.max' => 'Jumlah baris kosong paling banyak 20.',
            'aksi.in' => 'Pilihan tampilan berkas tidak dikenal.',
        ];
    }

    /**
     * Nama bidang yang dipilih, kalau penyaringnya per bidang.
     */
    public function namaBidang(): ?string
    {
        $bidangId = $this->input('bidang_id');

        if ($bidangId === null || $bidangId === '') {
            return null;
        }

        return Bidang::whereKey($bidangId)->value('nama');
    }
}
