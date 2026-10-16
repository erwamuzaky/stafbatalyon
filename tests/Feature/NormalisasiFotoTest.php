<?php

namespace Tests\Feature;

use App\Support\Foto\NormalisasiFoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NormalisasiFotoTest extends TestCase
{
    public function test_gambar_besar_dikecilkan_proporsional(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('besar.jpg', 1200, 900);

        $path = NormalisasiFoto::simpan($file);

        Storage::disk('public')->assertExists($path);

        $gambar = imagecreatefromstring(Storage::disk('public')->get($path));

        $this->assertNotFalse($gambar);
        $this->assertSame(800, imagesx($gambar));
        $this->assertSame(600, imagesy($gambar));
    }

    public function test_gambar_kecil_tidak_diperbesar(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('kecil.jpg', 300, 200);

        $path = NormalisasiFoto::simpan($file);

        $gambar = imagecreatefromstring(Storage::disk('public')->get($path));

        $this->assertSame(300, imagesx($gambar));
        $this->assertSame(200, imagesy($gambar));
    }

    public function test_png_tetap_png_dan_dikecilkan(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('besar.png', 1000, 500);

        $path = NormalisasiFoto::simpan($file);

        $this->assertStringEndsWith('.png', $path);
        Storage::disk('public')->assertExists($path);

        $gambar = imagecreatefromstring(Storage::disk('public')->get($path));

        $this->assertSame(800, imagesx($gambar));
        $this->assertSame(400, imagesy($gambar));
    }
}
