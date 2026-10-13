<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Staf Batalyon</title>

    <style>
        @page {
            margin: 18mm 15mm 18mm 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000000;
            line-height: 1.35;
        }

        .kop {
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 10px;
            text-align: center;
        }

        .kop .sekolah {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .kop .unit {
            font-size: 11px;
        }

        .kop .alamat {
            font-size: 9px;
        }

        .judul {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 0.6px;
            margin: 0 0 12px 0;
            text-decoration: underline;
        }

        table.rincian {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.rincian td {
            padding: 1px 0;
            vertical-align: top;
        }

        table.rincian td.label {
            width: 80px;
        }

        table.rincian td.pemisah {
            width: 8px;
        }

        table.hadir {
            width: 100%;
            border-collapse: collapse;
        }

        table.hadir thead {
            display: table-header-group;
        }

        table.hadir tr {
            page-break-inside: avoid;
        }

        table.hadir th,
        table.hadir td {
            border: 1px solid #000000;
            padding: 5px 6px;
            vertical-align: middle;
        }

        table.hadir th {
            background-color: #e5e7eb;
            font-size: 11px;
            text-align: center;
        }

        table.hadir td.baris {
            height: 36px;
        }

        .kolom-no {
            width: 8%;
            text-align: center;
        }

        .kolom-bidang {
            width: 24%;
        }

        .kolom-ttd {
            width: 38%;
        }

        .kosong {
            color: #9ca3af;
            font-style: italic;
        }

        table.tanda-tangan {
            width: 100%;
            margin-top: 18px;
            page-break-inside: avoid;
        }

        table.tanda-tangan td.bagian-kanan {
            width: 45%;
        }

        .blok-tanggal {
            margin: 0;
        }

        .blok-jabatan {
            margin: 0;
        }

        .blok-nama {
            margin: 48px 0 0 0;
            padding-bottom: 1px;
            border-bottom: 1px solid #000000;
            display: inline-block;
            min-width: 190px;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="sekolah">SMKN 3 Pariaman</div>
        <div class="unit">Staf Batalyon</div>
        <div class="alamat">Jalan Sutan Syahrir, Pariaman, Sumatera Barat</div>
    </div>

    <div class="judul">DAFTAR HADIR STAF BATALYON</div>

    <table class="rincian">
        <tr>
            <td class="label">Kegiatan</td>
            <td class="pemisah">:</td>
            <td>{{ $namaKegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="pemisah">:</td>
            <td>{{ $tanggal->translatedFormat('l, j F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Peserta</td>
            <td class="pemisah">:</td>
            <td>{{ $keteranganPeserta }}</td>
        </tr>
    </table>

    <table class="hadir">
        <thead>
            <tr>
                <th class="kolom-no">No</th>
                <th>Nama</th>
                <th class="kolom-bidang">Bidang</th>
                <th class="kolom-ttd">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @php($nomor = 1)

            @forelse ($peserta as $orang)
                <tr>
                    <td class="kolom-no baris">{{ $nomor++ }}</td>
                    <td class="baris">{{ $orang->name }}</td>
                    <td class="kolom-bidang baris">{{ $orang->stafProfile?->bidang?->nama ?? '-' }}</td>
                    <td class="kolom-ttd baris">&nbsp;</td>
                </tr>
            @empty
                <tr>
                    <td class="kolom-no baris">1</td>
                    <td class="baris kosong">Belum ada peserta pada penyaring ini</td>
                    <td class="kolom-bidang baris kosong">-</td>
                    <td class="kolom-ttd baris">&nbsp;</td>
                </tr>
                @php($nomor = 2)
            @endforelse

            @for ($i = 0; $i < $barisKosong; $i++)
                <tr>
                    <td class="kolom-no baris">{{ $nomor++ }}</td>
                    <td class="baris">&nbsp;</td>
                    <td class="kolom-bidang baris">&nbsp;</td>
                    <td class="kolom-ttd baris">&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td></td>
            <td class="bagian-kanan">
                <p class="blok-tanggal">{{ $tempat }}, {{ $tanggal->translatedFormat('j F Y') }}</p>
                <p class="blok-jabatan">{{ $jabatan }}</p>
                <p class="blok-nama">{{ $penandatangan }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
