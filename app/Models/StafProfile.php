<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StafProfile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staf_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'bidang_id',
        'atasan_id',
        'kelas',
        'jabatan',
        'is_inti',
        'urutan',
        'no_hp',
        'foto',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_inti' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * Get the user that owns the staf profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bidang of the staf profile.
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Atasan langsung staf ini (profil staf lain di tabel yang sama).
     */
    public function atasan(): BelongsTo
    {
        return $this->belongsTo(StafProfile::class, 'atasan_id');
    }

    /**
     * Daftar staf yang menganggap profil ini sebagai atasan langsungnya.
     */
    public function bawahan(): HasMany
    {
        return $this->hasMany(StafProfile::class, 'atasan_id');
    }

    /**
     * Cek apakah memasang $atasanId sebagai atasan profil ini akan membuat
     * lingkaran (saling meng atasi).
     *
     * Caranya: naik terus ke atas mulai dari calon atasan lewat kolom
     * "atasan_id". Kalau di tengah jalan ketemu profil ini sendiri, berarti
     * rantainya berputar balik ke diri sendiri, jadi tidak boleh dipasang.
     *
     * Data lama yang sudah lingkar sendiri (bukan melibatkan profil ini)
     * tidak dianggap masalah, jadi rantai seperti itu berhenti dengan aman.
     */
    public function punyaLingkaran(int $atasanId): bool
    {
        $diriId = (int) $this->getKey();
        $sekarang = $atasanId;
        $dilihat = [];

        while ($sekarang > 0) {
            if ($sekarang === $diriId) {
                return true;
            }

            // Sudah pernah dilewati: rantai lama ini lingkar sendiri,
            // tidak berkaitan dengan profil ini.
            if (isset($dilihat[$sekarang])) {
                return false;
            }

            $dilihat[$sekarang] = true;

            $berikutnya = static::query()->whereKey($sekarang)->value('atasan_id');
            $sekarang = $berikutnya === null ? 0 : (int) $berikutnya;
        }

        return false;
    }

    /**
     * URL publik foto profil. Null kalau belum ada foto.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->foto ? asset('storage/'.$this->foto) : null
        );
    }
}
