<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SerahTerima extends Model
{
    /**
     * The table associated with the model.
     *
     * Note: the table name is not pluralized.
     *
     * @var string
     */
    protected $table = 'serah_terima';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'periode_id',
        'periode_lama_id',
        'tanggal',
        'tempat',
        'catatan',
        'file_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Hapus berkas fisik setiap kali data serah terima dihapus.
     */
    protected static function booted(): void
    {
        static::deleting(function (SerahTerima $serahTerima) {
            if ($serahTerima->file_path) {
                Storage::disk('public')->delete($serahTerima->file_path);
            }
        });
    }

    /**
     * Get the periode that received the handover.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    /**
     * Get the previous periode that handed over.
     */
    public function periodeLama(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_lama_id');
    }
}
