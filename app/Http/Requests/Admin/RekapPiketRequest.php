<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RekapPiketRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh membuka rekap piket.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi penyaring rekap piket.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
            'bidang_id' => ['nullable', 'integer', Rule::exists('bidangs', 'id')],
        ];
    }

    /**
     * Pastikan rentang tanggal tidak terbalik.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if (! $this->filled('dari') || ! $this->filled('sampai')) {
                    return;
                }

                $dari = Carbon::parse((string) $this->input('dari'));
                $sampai = Carbon::parse((string) $this->input('sampai'));

                if ($dari->greaterThan($sampai)) {
                    $validator->errors()->add(
                        'sampai',
                        'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
                    );
                }
            },
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
            'dari.date' => 'Tanggal awal tidak sah.',
            'sampai.date' => 'Tanggal akhir tidak sah.',
            'bidang_id.integer' => 'Bidang yang dipilih tidak sah.',
            'bidang_id.exists' => 'Bidang yang dipilih tidak dikenal.',
        ];
    }
}
