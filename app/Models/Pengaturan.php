<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaturan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kunci',
        'nilai',
    ];

    /**
     * Nilai bawaan tiap kunci. Dipakai kalau admin belum mengisi,
     * supaya halaman depan tetap tampil normal.
     *
     * @var array<string, string>
     */
    public const DEFAULTS = [
        // Identitas organisasi dan sekolah (dipakai di navbar, hero, footer, judul tab).
        'situs_nama' => 'Staf Batalyon',
        'situs_sekolah' => 'SMKN 3 Pariaman',

        // Hero.
        'hero_label' => 'Profil Organisasi',
        'hero_pengantar' => 'Organisasi kesiswaan yang menjaga ketertiban harian sekolah dan menjalankan kegiatan serta kepanitiaan sepanjang tahun ajaran.',
        'hero_background' => '',

        // Tentang.
        'tentang_label' => 'Tentang',
        'tentang_judul' => 'Menjaga ketertiban, menjalankan kegiatan',
        'tentang_pengantar' => 'Staf Batalyon adalah organisasi kesiswaan SMKN 3 Pariaman yang membantu sekolah menjaga ketertiban harian dan memastikan setiap kegiatan berjalan tertib, dari persiapan sampai arsipnya tersimpan.',
        'tentang_fitur1_judul' => 'Pengamanan gerbang',
        'tentang_fitur1_teks' => 'Mengatur jadwal dan menjalankan piket gerbang harian bersama seluruh staf.',
        'tentang_fitur2_judul' => 'Pelaksanaan kegiatan',
        'tentang_fitur2_teks' => 'Membantu kepanitiaan dan memastikan kegiatan sekolah berjalan lancar.',
        'tentang_fitur3_judul' => 'Administrasi',
        'tentang_fitur3_teks' => 'Menyimpan arsip proposal dan merapikan data keanggotaan batalyon.',

        // Struktur staf.
        'struktur_judul' => 'Struktur Staf',
        'struktur_subjudul' => 'Staf inti yang memimpin kepengurusan periode berjalan.',

        // Bidang.
        'bidang_judul' => 'Bidang',
        'bidang_subjudul' => 'Bidang-bidang yang menaungi seluruh staf batalyon.',

        // Satu foto latar dipakai bersama oleh bagian Struktur Staf dan Bidang.
        'bagian_background' => '',

        // Foto latar khusus halaman Struktur Lengkap. Kalau kosong, halaman itu
        // memakai foto latar bagian (bagian_background) sebagai cadangan.
        'struktur_lengkap_background' => '',

        // Tingkat kegelapan lapisan gelap di atas foto latar bagian (beranda
        // dan halaman Struktur Lengkap). Salah satu: samar, sedang, tegas.
        'latar_kepekatan' => 'sedang',

        // Pita ajakan daftar.
        'ajakan_judul' => 'Baru ditunjuk sebagai staf?',
        'ajakan_teks' => 'Daftar akun staf dulu, lalu tunggu persetujuan admin sebelum bisa masuk.',

        // Footer.
        'footer_nama' => 'Staf Batalyon',
        'footer_sekolah' => 'SMKN 3 Pariaman',
        'footer_peta_kata_kunci' => 'SMK Negeri 3 Pariaman',
        'footer_peta_catatan' => 'Klik peta untuk buka di Google Maps',
        'footer_hak_cipta' => 'Staf Batalyon SMKN 3 Pariaman',
    ];

    /**
     * Pasangan kunci gambar latar beranda => kunci checkbox penghapusnya.
     *
     * @var array<string, string>
     */
    public const LATAR_BERANDA = [
        'hero_background' => 'hapus_background',
        'bagian_background' => 'hapus_background_bagian',
        'struktur_lengkap_background' => 'hapus_background_struktur',
    ];

    /**
     * Pilihan tingkat kegelapan lapisan gelap di atas foto latar bagian,
     * dipakai bersama oleh validasi dan formulir pengaturan admin.
     *
     * @var array<string, string>
     */
    public const LATAR_KEPEKATAN = [
        'samar' => 'Samar',
        'sedang' => 'Sedang',
        'tegas' => 'Tegas',
    ];

    /**
     * Semua pengaturan sebagai pasangan kunci => nilai, sudah digabung
     * dengan nilai bawaan. Nilai kosong dianggap belum diisi.
     *
     * @return array<string, string>
     */
    public static function peta(): array
    {
        $hasil = self::DEFAULTS;

        foreach (static::query()->pluck('nilai', 'kunci') as $kunci => $nilai) {
            $nilai = is_string($nilai) ? trim($nilai) : $nilai;

            if ($nilai !== null && $nilai !== '') {
                $hasil[$kunci] = $nilai;
            }
        }

        return $hasil;
    }

    /**
     * Ambil satu nilai pengaturan. Kosong berarti kembali ke nilai bawaan.
     */
    public static function ambil(string $kunci, ?string $default = null): ?string
    {
        return static::peta()[$kunci] ?? $default;
    }

    /**
     * Simpan banyak pengaturan sekaligus.
     *
     * @param  array<string, string|null>  $nilai
     */
    public static function simpan(array $nilai): void
    {
        foreach ($nilai as $kunci => $isi) {
            static::query()->updateOrCreate(
                ['kunci' => $kunci],
                ['nilai' => is_string($isi) ? trim($isi) : $isi]
            );
        }
    }

    /**
     * URL publik sebuah gambar pengaturan (mis. background hero).
     * Null kalau belum diisi.
     */
    public static function gambar(string $kunci): ?string
    {
        $path = static::peta()[$kunci] ?? '';

        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        // Pakai asset() supaya URL mengikuti alamat (host + port) request saat ini,
        // bukan APP_URL. URL dari disk storage bawaan sering kehilangan port dev server.
        return asset('storage/'.$path);
    }
}
