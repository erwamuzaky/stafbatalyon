<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
    ];

    /**
     * Get the staf profiles that belong to the bidang.
     */
    public function stafProfiles(): HasMany
    {
        return $this->hasMany(StafProfile::class);
    }
}
