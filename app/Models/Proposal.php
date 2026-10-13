<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Proposal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'judul',
        'file_path',
        'catatan',
        'template_dari_id',
    ];

    /**
     * Hapus berkas fisik setiap kali data proposal dihapus.
     */
    protected static function booted(): void
    {
        static::deleting(function (Proposal $proposal) {
            if ($proposal->file_path) {
                Storage::disk('public')->delete($proposal->file_path);
            }
        });
    }

    /**
     * Get the event of the proposal.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the old proposal used as the base of this proposal.
     */
    public function templateDari(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'template_dari_id');
    }

    /**
     * Get the proposals that were created from this proposal.
     */
    public function turunan(): HasMany
    {
        return $this->hasMany(Proposal::class, 'template_dari_id');
    }
}
