<?php

namespace App\Http\Requests\Admin;

use App\Models\Periode;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriodeRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh menambah periode.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk menambah periode.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2099'],
            'tahun_selesai' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2099', 'gte:tahun_mulai'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', Rule::in(array_keys(Periode::STATUS))],
            'catatan' => ['nullable', 'string', 'max:2000'],
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
            'nama.required' => 'Nama periode wajib diisi.',
            'nama.max' => 'Nama periode terlalu panjang, maksimal 255 karakter.',
            'tahun_mulai.required' => 'Tahun mulai wajib diisi.',
            'tahun_mulai.digits' => 'Tahun harus terdiri dari 4 angka.',
            'tahun_selesai.required' => 'Tahun selesai wajib diisi.',
            'tahun_selesai.digits' => 'Tahun harus terdiri dari 4 angka.',
            'tahun_selesai.gte' => 'Tahun selesai tidak boleh lebih awal dari tahun mulai.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'status.required' => 'Status periode wajib dipilih.',
            'status.in' => 'Status periode tidak valid.',
            'catatan.max' => 'Catatan terlalu panjang, maksimal 2000 karakter.',
        ];
    }
}
