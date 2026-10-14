<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
        'kelas',
        'jabatan',
        'is_inti',
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
     * URL publik foto profil. Null kalau belum ada foto.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->foto ? Storage::disk('public')->url($this->foto) : null
        );
    }
}
