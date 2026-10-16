<?php

namespace App\Support\Foto;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NormalisasiFoto
{
    /** Maks lebar/tinggi piksel hasil resize. */
    public const MAX_DIMENSI = 800;

    /** Kualitas JPEG/WebP (1-100). */
    public const KUALITAS = 85;

    /**
     * Resize gambar proportional, simpan ke disk public/staf, kembalikan path relatif.
     *
     * @return string path relatif di disk public (mis. 'staf/abc123.jpg')
     */
    public static function simpan(UploadedFile $berkas): string
    {
        $mime = $berkas->getMimeType();
        $ekstensi = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        // Baca sumber
        $sumber = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($berkas->getPathname()),
            'image/png' => imagecreatefrompng($berkas->getPathname()),
            'image/webp' => imagecreatefromwebp($berkas->getPathname()),
            default => null,
        };

        if (! $sumber) {
            throw new \RuntimeException('Gagal membaca berkas gambar.');
        }

        $lebarAsli = imagesx($sumber);
        $tinggiAsli = imagesy($sumber);

        // Hitung skala
        $skala = min(self::MAX_DIMENSI / $lebarAsli, self::MAX_DIMENSI / $tinggiAsli, 1.0);
        $lebarBaru = (int) round($lebarAsli * $skala);
        $tinggiBaru = (int) round($tinggiAsli * $skala);

        // Buat kanvas baru
        $kanvas = imagecreatetruecolor($lebarBaru, $tinggiBaru);

        // Jaga transparansi PNG/WebP
        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($kanvas, false);
            imagesavealpha($kanvas, true);
            $transparan = imagecolorallocatealpha($kanvas, 255, 255, 255, 127);
            imagefill($kanvas, 0, 0, $transparan);
        }

        imagecopyresampled($kanvas, $sumber, 0, 0, 0, 0, $lebarBaru, $tinggiBaru, $lebarAsli, $tinggiAsli);
        imagedestroy($sumber);

        // Tulis ke buffer
        ob_start();
        match ($mime) {
            'image/jpeg' => imagejpeg($kanvas, null, self::KUALITAS),
            'image/png' => imagepng($kanvas, null, 9),
            'image/webp' => imagewebp($kanvas, null, self::KUALITAS),
            default => imagejpeg($kanvas, null, self::KUALITAS),
        };
        $konten = ob_get_clean();
        imagedestroy($kanvas);

        // Nama acak + ekstensi asli
        $nama = $berkas->hashName();
        $pathRelatif = 'staf/'.$nama;

        Storage::disk('public')->put($pathRelatif, $konten);

        return $pathRelatif;
    }
}
