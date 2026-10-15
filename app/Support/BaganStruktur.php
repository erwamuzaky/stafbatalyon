<?php

namespace App\Support;

use App\Models\StafProfile;
use Illuminate\Support\Collection;

/**
 * Penyusun bagan struktur staf dari kolom "atasan_id".
 *
 * Dipakai halaman struktur lengkap (semua staf tampil) dan halaman depan
 * (versi ringkas: hanya sebagian staf yang disaring pemanggil, dibatasi
 * beberapa tingkat).
 */
class BaganStruktur
{
    /**
     * Semua profil staf yang sudah disetujui, lengkap dengan user dan bidang.
     *
     * Urutan mengikuti kolom "urutan" (terkecil dulu, yang kosong di
     * belakang), lalu nama. Nomor HP dan email tidak ikut diambil.
     *
     * @return Collection<int, StafProfile>
     */
    public static function profilsDisetujui(): Collection
    {
        return StafProfile::query()
            ->with(['user', 'bidang'])
            ->whereHas('user', fn ($query) => $query->where('role', 'staf')->where('status', 'approved'))
            ->get()
            ->sortBy([
                fn (StafProfile $a, StafProfile $b) => ($a->urutan ?? PHP_INT_MAX) <=> ($b->urutan ?? PHP_INT_MAX),
                fn (StafProfile $a, StafProfile $b) => strcmp($a->user?->name ?? '', $b->user?->name ?? ''),
            ])
            ->values();
    }

    /**
     * Susun daftar profil jadi bagan bertingkat: tiap simpul berisi satu
     * profil beserta daftar bawahannya (rekursif).
     *
     * Staf tanpa atasan langsung (atau atasannya tidak ikut tampil, atau
     * atasannya dirinya sendiri) jadi simpul paling atas. Profil yang belum
     * sempat masuk bagan - misalnya karena datanya lingkar sendiri -
     * dinaikkan jadi simpul teratas supaya tidak ada staf yang hilang.
     *
     * Kalau $kedalamanMaks diisi, staf yang berada di luar batas tingkat
     * tetap disembunyikan (tidak dinaikkan jadi simpul teratas).
     *
     * @param  Collection<int, StafProfile>  $profils
     * @param  int|null  $kedalamanMaks  Batas jumlah tingkat (null = tanpa batas).
     * @return Collection<int, array{profil: StafProfile, anak: Collection<int, mixed>}>
     */
    public static function susun(Collection $profils, ?int $kedalamanMaks = null): Collection
    {
        $anakPerAtasan = [];
        $akar = [];

        foreach ($profils as $profil) {
            $idProfil = (int) $profil->getKey();
            $idAtasan = $profil->atasan_id === null ? null : (int) $profil->atasan_id;

            if ($idAtasan === null || $idAtasan === $idProfil || ! $profils->contains('id', $idAtasan)) {
                $akar[] = $profil;

                continue;
            }

            $anakPerAtasan[$idAtasan][] = $profil;
        }

        $sudahDibuat = [];

        $buatSimpul = function (StafProfile $profil, int $kedalaman) use (&$buatSimpul, $anakPerAtasan, &$sudahDibuat, $kedalamanMaks): array {
            $idProfil = (int) $profil->getKey();
            $sudahDibuat[$idProfil] = true;

            $masihBolehTurun = $kedalamanMaks === null || $kedalaman < $kedalamanMaks;

            $anak = $masihBolehTurun
                ? collect($anakPerAtasan[$idProfil] ?? [])
                    ->reject(fn (StafProfile $bawahan) => isset($sudahDibuat[(int) $bawahan->getKey()]))
                    ->map(fn (StafProfile $bawahan) => $buatSimpul($bawahan, $kedalaman + 1))
                    ->values()
                : collect();

            return ['profil' => $profil, 'anak' => $anak];
        };

        $bagan = collect($akar)->map(fn (StafProfile $profil) => $buatSimpul($profil, 1))->values();

        // Tandai semua staf yang bisa dicapai dari akar tanpa batas tingkat.
        // Dengan begitu, staf yang cuma terpotong batas tingkat tidak ikut
        // dinaikkan; hanya data yang benar-benar belum masuk bagan (mis.
        // lingkar sendiri) yang dinaikkan jadi simpul teratas.
        $terjangkauDariAkar = [];

        $tandaiTerjangkau = function (StafProfile $profil) use (&$tandaiTerjangkau, $anakPerAtasan, &$terjangkauDariAkar): void {
            $idProfil = (int) $profil->getKey();

            if (isset($terjangkauDariAkar[$idProfil])) {
                return;
            }

            $terjangkauDariAkar[$idProfil] = true;

            foreach ($anakPerAtasan[$idProfil] ?? [] as $bawahan) {
                $tandaiTerjangkau($bawahan);
            }
        };

        foreach ($akar as $profil) {
            $tandaiTerjangkau($profil);
        }

        foreach ($profils as $profil) {
            $idProfil = (int) $profil->getKey();

            if (! isset($terjangkauDariAkar[$idProfil]) && ! isset($sudahDibuat[$idProfil])) {
                $bagan->push($buatSimpul($profil, 1));
            }
        }

        return $bagan;
    }
}
