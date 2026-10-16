<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Piket Gerbang</title>

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
            width: 130px;
        }

        table.rincian td.pemisah {
            width: 8px;
        }

        table.rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.rekap thead {
            display: table-header-group;
        }

        table.rekap tr {
            page-break-inside: avoid;
        }

        table.rekap th,
        table.rekap td {
            border: 1px solid #000000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        table.rekap th {
            background-color: #e5e7eb;
            font-size: 11px;
            text-align: center;
        }

        table.rekap td.angka {
            text-align: center;
        }

        .kolom-no {
            width: 6%;
            text-align: center;
        }

        .kolom-total {
            width: 9%;
        }

        .kolom-sesi {
            width: 13%;
        }

        .kosong {
            color: #9ca3af;
            font-style: italic;
        }

        .bagian-perhatian {
            margin-top: 12px;
        }

        .bagian-perhatian h3 {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 4px 0;
        }

        .bagian-perhatian ul {
            margin: 0 0 8px 0;
            padding-left: 16px;
        }

        .bagian-perhatian li {
            margin-bottom: 2px;
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
        <div class="alamat">Jl. Siti Manggopoh, Manggung, Kec. Pariaman Utara, Kota Pariaman, Sumatera Barat 25522</div>
    </div>

    <div class="judul">REKAP PIKET GERBANG</div>

    <table class="rincian">
        <tr>
            <td class="label">Rentang</td>
            <td class="pemisah">:</td>
            <td>{{ $dari->translatedFormat('j F Y') }} &ndash; {{ $sampai->translatedFormat('j F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Bidang</td>
            <td class="pemisah">:</td>
            <td>{{ $namaBidang ? 'Bidang '.$namaBidang : 'Semua bidang' }}</td>
        </tr>
        <tr>
            <td class="label">Total slot piket</td>
            <td class="pemisah">:</td>
            <td>{{ $ringkasan['total'] }} slot</td>
        </tr>
        <tr>
            <td class="label">Staf dapat piket</td>
            <td class="pemisah">:</td>
            <td>{{ $ringkasan['jumlahStaf'] }} staf</td>
        </tr>
        <tr>
            <td class="label">Rata-rata per staf</td>
            <td class="pemisah">:</td>
            <td>{{ number_format($ringkasan['rataRata'], 1, ',', '.') }} piket</td>
        </tr>
        <tr>
            <td class="label">Piket terbanyak</td>
            <td class="pemisah">:</td>
            <td>{{ $ringkasan['terbanyak'] }} piket{{ $ringkasan['namaTerbanyak'] ? ' ('.implode(', ', $ringkasan['namaTerbanyak']).')' : '' }}</td>
        </tr>
        <tr>
            <td class="label">Piket paling sedikit</td>
            <td class="pemisah">:</td>
            <td>{{ $ringkasan['tersedikit'] }} piket{{ $ringkasan['namaTersedikit'] ? ' ('.implode(', ', $ringkasan['namaTersedikit']).')' : '' }}</td>
        </tr>
    </table>

    <table class="rekap">
        <thead>
            <tr>
                <th class="kolom-no">No</th>
                <th>Nama</th>
                <th>Bidang</th>
                <th class="kolom-total">Total</th>
                @foreach ($sesi as $label)
                    <th class="kolom-sesi">{{ $label }}</th>
                @endforeach
                <th>Tanggal Piket</th>
            </tr>
        </thead>
        <tbody>
            @php($nomor = 1)

            @forelse ($tabel as $baris)
                <tr>
                    <td class="kolom-no">{{ $nomor++ }}</td>
                    <td>{{ $baris['user']->name }}</td>
                    <td>{{ $baris['user']->stafProfile?->bidang?->nama ?? '-' }}</td>
                    <td class="kolom-total angka">{{ $baris['total'] }}</td>
                    @foreach ($sesi as $kode => $label)
                        <td class="kolom-sesi angka">{{ $baris['perSesi'][$kode] ?? 0 }}</td>
                    @endforeach
                    <td>
                        {{ implode(', ', array_map(fn ($tgl) => $tgl->translatedFormat('d/m/Y'), array_slice($baris['tanggal'], 0, $batasTanggal))) }}
                        @if (count($baris['tanggal']) > $batasTanggal)
                            (+{{ count($baris['tanggal']) - $batasTanggal }} tanggal lain)
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="kolom-no">1</td>
                    <td class="kosong" colspan="{{ count($sesi) + 4 }}">Belum ada piket pada rentang ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="bagian-perhatian">
        <h3>Perlu diperhatikan</h3>

        <p class="blok-jabatan">Staf dengan piket pada hari berurutan:</p>
        @if ($berurutan !== [])
            <ul>
                @foreach ($berurutan as $item)
                    <li>
                        {{ $item['user']->name }} &ndash;
                        @foreach ($item['rentang'] as $rentang)
                            {{ $rentang['dari']->translatedFormat('d/m/Y') }} s.d. {{ $rentang['sampai']->translatedFormat('d/m/Y') }}
                            ({{ $rentang['jumlah'] }} hari){{ ! $loop->last ? '; ' : '' }}
                        @endforeach
                    </li>
                @endforeach
            </ul>
        @else
            <p class="kosong">Tidak ada staf dengan piket pada hari berurutan.</p>
        @endif

        <p class="blok-jabatan">Staf disetujui yang belum dapat piket:</p>
        @if ($belum !== [])
            <ul>
                @foreach ($belum as $staf)
                    <li>{{ $staf->name }}{{ $staf->stafProfile?->bidang?->nama ? ' ('.$staf->stafProfile->bidang->nama.')' : '' }}</li>
                @endforeach
            </ul>
        @else
            <p class="kosong">Semua staf yang dapat piket sudah mendapat jadwal pada rentang ini.</p>
        @endif
    </div>

    <table class="tanda-tangan">
        <tr>
            <td></td>
            <td class="bagian-kanan">
                <p class="blok-tanggal">{{ $tempat }}, {{ $sampai->translatedFormat('j F Y') }}</p>
                <p class="blok-jabatan">{{ $jabatan }}</p>
                <p class="blok-nama">&nbsp;</p>
            </td>
        </tr>
    </table>
</body>
</html>
