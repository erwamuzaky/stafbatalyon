<?php

namespace App\Http\Requests\Admin;

use App\Models\PiketGerbang;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePiketRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah piket.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk mengubah entri piket.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'sesi' => ['required', Rule::in(array_keys(PiketGerbang::SESI))],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'staf')->where('status', 'approved'),
            ],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Cek bentrok slot dengan mengabaikan entri yang sedang diubah.
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

                $piket = $this->route('piket');

                $bentrok = PiketGerbang::cekBentrok(
                    (string) $this->input('tanggal'),
                    (string) $this->input('sesi'),
                    [(int) $this->input('user_id')],
                    $piket instanceof PiketGerbang ? $piket->getKey() : null,
                );

                if ($bentrok->isEmpty()) {
                    return;
                }

                $validator->errors()->add(
                    'user_id',
                    'Staf ini sudah mendapat piket pada tanggal dan sesi yang sama.',
                );
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
            'tanggal.required' => 'Tanggal piket wajib diisi.',
            'tanggal.date' => 'Tanggal piket tidak sah.',
            'sesi.required' => 'Sesi piket wajib dipilih.',
            'sesi.in' => 'Sesi piket yang dipilih tidak dikenal.',
            'user_id.required' => 'Staf yang bertugas wajib dipilih.',
            'user_id.integer' => 'Staf yang dipilih tidak sah.',
            'user_id.exists' => 'Staf yang dipilih tidak dikenal atau akunnya belum disetujui.',
            'catatan.max' => 'Catatan terlalu panjang, maksimal 1000 karakter.',
        ];
    }
}
