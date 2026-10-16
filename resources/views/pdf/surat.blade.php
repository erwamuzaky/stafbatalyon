<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat {{ $surat->nomor }}</title>
    <style>
        @page { margin: 18mm 16mm 18mm 16mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000000;
            line-height: 1.5;
        }
        .kop {
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 12px;
            text-align: center;
        }
        .kop .sekolah { font-size: 17px; font-weight: bold; letter-spacing: 0.4px; }
        .kop .unit { font-size: 12px; font-weight: bold; margin-top: 2px; }
        .kop .alamat { font-size: 9px; margin-top: 2px; }

        .nomor-tanggal {
            margin: 8px 0 14px 0;
        }
        .nomor-tanggal table { width: 100%; border-collapse: collapse; }
        .nomor-tanggal td { padding: 1px 0; vertical-align: top; }
        .nomor-tanggal td.label { width: 80px; }
        .nomor-tanggal td.pemisah { width: 8px; }

        .perihal {
            margin: 4px 0 14px 0;
        }
        .perihal table { width: 100%; border-collapse: collapse; }
        .perihal td { padding: 1px 0; vertical-align: top; }
        .perihal td.label { width: 80px; }
        .perihal td.pemisah { width: 8px; }

        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 14px 0;
            letter-spacing: 0.4px;
        }

        .isi {
            margin: 10px 0 14px 0;
            text-align: justify;
            white-space: pre-line;
        }

        .penutup {
            margin: 14px 0 0 0;
            text-align: justify;
        }

        .ttd {
            width: 100%;
            margin-top: 24px;
            page-break-inside: avoid;
        }
        .ttd .kid { width: 55%; }
        .ttd .kanan { width: 45%; text-align: center; }
        .ttd .tempat { margin: 0 0 4px 0; }
        .ttd .jabatan { margin: 0 0 52px 0; }
        .ttd .nama {
            margin: 0;
            display: inline-block;
            min-width: 190px;
            border-bottom: 1px solid #000000;
            padding-bottom: 1px;
            font-weight: bold;
        }
        .ttd .nid { margin: 2px 0 0 0; font-size: 9px; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="sekolah">{{ $kopSekolah }}</div>
        <div class="unit">{{ $kopNama }}</div>
        <div class="alamat">Jl. Siti Manggopoh, Manggung, Kec. Pariaman Utara, Kota Pariaman, Sumatera Barat 25522</div>
    </div>

    <div class="judul">{{ $surat->labelJenis() }}</div>

    <div class="nomor-tanggal">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="pemisah">:</td>
                <td>{{ $surat->nomor }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="pemisah">:</td>
                <td>{{ $surat->tempat }}, {{ $surat->tanggal->translatedFormat('j F Y') }}</td>
            </tr>
            @if ($surat->tujuan)
                <tr>
                    <td class="label">Kepada</td>
                    <td class="pemisah">:</td>
                    <td>{{ $surat->tujuan }}</td>
                </tr>
            @endif
            @if ($surat->periode)
                <tr>
                    <td class="label">Periode</td>
                    <td class="pemisah">:</td>
                    <td>{{ $surat->periode->nama }} ({{ $surat->periode->tahun_mulai }}/{{ $surat->periode->tahun_selesai }})</td>
                </tr>
            @endif
            @if ($surat->event)
                <tr>
                    <td class="label">Kegiatan</td>
                    <td class="pemisah">:</td>
                    <td>{{ $surat->event->nama }} ({{ $surat->event->tahun }})</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="perihal">
        <table>
            <tr>
                <td class="label">Perihal</td>
                <td class="pemisah">:</td>
                <td>{{ $surat->perihal }}</td>
            </tr>
        </table>
    </div>

    <div class="isi">
        {{ trim($surat->isi ?? '') === '' ? 'Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.' : $surat->isi }}
    </div>

    <table class="ttd">
        <tr>
            <td class="kid">&nbsp;</td>
            <td class="kanan">
                <p class="tempat">{{ $surat->tempat }}, {{ $surat->tanggal->translatedFormat('j F Y') }}</p>
                <p class="jabatan">{{ $surat->jabatan }}</p>
                <p class="nama">{{ $surat->penandatangan }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
