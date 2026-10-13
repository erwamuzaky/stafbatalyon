<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiketGerbang extends Model
{
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
}
