<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    /**
     * Status periode beserta labelnya.
     *
     * @var array<string, string>
     */
    public const STATUS = [
        'aktif' => 'Aktif',
        'selesai' => 'Selesai',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_mulai' => 'integer',
            'tahun_selesai' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /**
     * Get the events that belong to the periode.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the handovers received by this periode.
     */
    public function serahTerima(): HasMany
    {
        return $this->hasMany(SerahTerima::class);
    }

    /**
     * Get the handovers where this periode is the previous one.
     */
    public function serahTerimaSebagaiLama(): HasMany
    {
        return $this->hasMany(SerahTerima::class, 'periode_lama_id');
    }

    /**
     * Apakah periode ini yang sedang aktif.
     */
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
