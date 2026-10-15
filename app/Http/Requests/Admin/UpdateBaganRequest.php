<?php

namespace App\Http\Requests\Admin;

use App\Models\StafProfile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBaganRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengatur bagan atasan.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Isian berupa daftar pasangan: key = id profil staf, value = id atasan.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'atasan' => ['nullable', 'array'],
            'atasan.*' => ['nullable', 'integer', Rule::exists('staf_profiles', 'id')],
        ];
    }

    /**
     * Pemeriksaan tambahan: key harus id profil yang sah, tidak boleh
     * menjadikan diri sendiri atasan, dan tidak boleh membuat lingkaran.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $data = $this->input('atasan');

                if (! is_array($data)) {
                    return;
                }

                // Key (id profil yang diubah) harus id profil staf yang ada.
                $keyAda = StafProfile::query()
                    ->whereIn('id', array_keys($data))
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $keyTidakSah = false;
                foreach (array_keys($data) as $id) {
                    if (! in_array((int) $id, $keyAda, true)) {
                        $keyTidakSah = true;
                        break;
                    }
                }

                if ($keyTidakSah) {
                    $validator->errors()->add(
                        'atasan',
                        'Ada staf yang tidak dikenal pada isian bagan.',
                    );

                    return;
                }

                foreach ($data as $id => $atasanId) {
                    if ($atasanId === null || $atasanId === '') {
                        continue;
                    }

                    $id = (int) $id;
                    $atasanId = (int) $atasanId;

                    if ($atasanId === $id) {
                        $validator->errors()->add(
                            "atasan.$id",
                            'Staf tidak bisa menjadi atasan dirinya sendiri.',
                        );

                        continue;
                    }

                    $profil = StafProfile::query()->find($id);

                    if ($profil && $profil->punyaLingkaran($atasanId)) {
                        $validator->errors()->add(
                            "atasan.$id",
                            'Pilihan ini membuat struktur saling meng atasi (lingkaran), jadi tidak bisa disimpan.',
                        );
                    }
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
            'atasan.array' => 'Isian bagan tidak sah.',
            'atasan.*.integer' => 'Pilihan atasan tidak sah.',
            'atasan.*.exists' => 'Atasan yang dipilih tidak dikenal.',
        ];
    }
}
