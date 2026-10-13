<?php

namespace App\Http\Requests\Admin;

use App\Models\PiketGerbang;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePiketRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengatur piket.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Aturan validasi untuk menambah entri piket.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'sesi' => ['required', Rule::in(array_keys(PiketGerbang::SESI))],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')->where('role', 'staf')->where('status', 'approved'),
            ],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Cek bentrok slot (tanggal + sesi + staf yang sama) setelah aturan dasar lolos.
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

                $bentrok = PiketGerbang::cekBentrok(
                    (string) $this->input('tanggal'),
                    (string) $this->input('sesi'),
                    (array) $this->input('user_ids', []),
                );

                if ($bentrok->isEmpty()) {
                    return;
                }

                $validator->errors()->add(
                    'user_ids',
                    'Staf berikut sudah mendapat piket pada tanggal dan sesi yang sama: '
                    .$bentrok->pluck('user.name')->implode(', ').'.',
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
            'user_ids.required' => 'Pilih minimal satu staf yang bertugas.',
            'user_ids.array' => 'Daftar staf tidak sah.',
            'user_ids.min' => 'Pilih minimal satu staf yang bertugas.',
            'user_ids.*.integer' => 'Staf yang dipilih tidak sah.',
            'user_ids.*.distinct' => 'Ada staf yang dipilih lebih dari sekali.',
            'user_ids.*.exists' => 'Staf yang dipilih tidak dikenal atau akunnya belum disetujui.',
            'catatan.max' => 'Catatan terlalu panjang, maksimal 1000 karakter.',
        ];
    }
}
