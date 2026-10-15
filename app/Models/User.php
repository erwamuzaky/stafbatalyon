<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Pilihan penyaring peserta absensi beserta labelnya.
     *
     * @var array<string, string>
     */
    public const PESERTA_FILTER = [
        'semua' => 'Semua staf',
        'inti' => 'Staf inti saja',
        'bidang' => 'Per bidang',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Apakah user ini admin (staf inti)?
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Apakah user ini staf biasa?
     */
    public function isStaf(): bool
    {
        return $this->role === 'staf';
    }

    /**
     * Apakah akun sudah disetujui admin?
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Get the staf profile of the user.
     */
    public function stafProfile(): HasOne
    {
        return $this->hasOne(StafProfile::class);
    }

    /**
     * Daftar peserta absensi: akun role staf yang sudah disetujui, urut nama.
     *
     * Penyaring yang didukung: semua staf, staf inti saja, atau satu bidang.
     *
     * @return Collection<int, User>
     */
    public static function pesertaAbsensi(string $filter, ?int $bidangId = null): Collection
    {
        return static::query()
            ->with('stafProfile.bidang')
            ->where('role', 'staf')
            ->where('status', 'approved')
            ->when(
                $filter === 'inti',
                fn ($query) => $query->whereHas(
                    'stafProfile',
                    fn ($profil) => $profil->where('is_inti', true),
                ),
            )
            ->when(
                $filter === 'bidang' && $bidangId !== null,
                fn ($query) => $query->whereHas(
                    'stafProfile',
                    fn ($profil) => $profil->where('bidang_id', $bidangId),
                ),
            )
            ->orderBy('name')
            ->get();
    }
}
