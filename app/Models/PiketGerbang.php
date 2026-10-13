<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class PiketGerbang extends Model
{
    /**
     * Sesi piket gerbang beserta labelnya.
     *
     * @var array<string, string>
     */
    public const SESI = [
        'pagi' => 'Pagi',
        'siang' => 'Siang',
    ];

    /**
     * The table associated with the model.
     *
     * Note: the table name is not pluralized.
     *
     * @var string
     */
    protected $table = 'piket_gerbang';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tanggal',
        'sesi',
        'user_id',
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
            'tanggal' => 'date',
        ];
    }

    /**
     * Get the user that is on duty.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cari entri yang bentrok pada tanggal dan sesi yang sama.
     *
     * Dipakai untuk mencegah satu staf mendapat dua piket pada slot yang sama.
     *
     * @param  array<int, int|string>  $userIds
     * @return Collection<int, PiketGerbang>
     */
    public static function cekBentrok(
        string $tanggal,
        string $sesi,
        array $userIds,
        ?int $kecualiId = null,
    ): Collection {
        return static::query()
            ->with('user')
            ->whereDate('tanggal', $tanggal)
            ->where('sesi', $sesi)
            ->whereIn('user_id', $userIds)
            ->when($kecualiId !== null, fn ($query) => $query->whereKeyNot($kecualiId))
            ->get();
    }
}
