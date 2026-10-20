<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    /**
     * Kelompok staf tempat bidang ini berada.
     *
     * @var array<string, string>
     */
    public const KELOMPOK = [
        'inti' => 'Staf inti (Danyon/Wadanyon/Danpol/Wadanpol/dst)',
        'harian' => 'Staf harian (bidang tugas harian)',
        'kelas' => 'Staf kelas (poltar/poltir/danton/dst)',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'tampil_di_beranda',
        'kelompok',
    ];

    /**
     * Casting atribut bidang.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tampil_di_beranda' => 'boolean',
        ];
    }

    /**
     * Get the staf profiles that belong to the bidang.
     */
    public function stafProfiles(): HasMany
    {
        return $this->hasMany(StafProfile::class);
    }
}
