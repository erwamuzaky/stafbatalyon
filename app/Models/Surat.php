<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Surat extends Model
{
    /**
     * Jenis surat beserta label dan kodenya.
     *
     * @var array<string, array{label:string,kode:string}>
     */
    public const JENIS = [
        'sk' => ['label' => 'Surat Keputusan (SK)', 'kode' => 'SK'],
        'tugas' => ['label' => 'Surat Tugas', 'kode' => 'ST'],
        'undangan' => ['label' => 'Surat Undangan', 'kode' => 'SU'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'jenis',
        'nomor_urut',
        'nomor',
        'tanggal',
        'perihal',
        'tujuan',
        'isi',
        'periode_id',
        'event_id',
        'penandatangan',
        'jabatan',
        'tempat',
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
     * Hapus berkas PDF setiap kali data surat dihapus.
     */
    protected static function booted(): void
    {
        static::deleting(function (Surat $surat) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }
        });
    }

    /**
     * Get the periode that the letter relates to.
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    /**
     * Get the event that the letter relates to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Label jenis surat.
     */
    public function labelJenis(): string
    {
        return self::JENIS[$this->jenis]['label'] ?? $this->jenis;
    }

    /**
     * Kode singkat jenis surat (SK, ST, SU).
     */
    public function kodeJenis(): string
    {
        return self::JENIS[$this->jenis]['kode'] ?? strtoupper($this->jenis);
    }

    /**
     * Buang nomor surat otomatis berdasarkan jenis dan tahun.
     *
     * @return string
     */
    public static function buatNomor(string $jenis, Carbon $tanggal): array
    {
        $kode = self::JENIS[$jenis]['kode'] ?? strtoupper($jenis);
        $tahun = $tanggal->year;
        $bulan = self::angkaKeRomawi($tanggal->month);

        $urut = (int) self::where('jenis', $jenis)
            ->whereYear('tanggal', $tahun)
            ->max('nomor_urut') + 1;

        return [
            'nomor_urut' => $urut,
            'nomor' => sprintf('%03d/%s/%s/%d', $urut, $kode, $bulan, $tahun),
        ];
    }

    /**
     * Ubah angka bulan menjadi romawi (1-12).
     */
    public static function angkaKeRomawi(int $bulan): string
    {
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return $romawi[$bulan] ?? (string) $bulan;
    }
}
