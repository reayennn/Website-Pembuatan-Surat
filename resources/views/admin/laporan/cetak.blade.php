<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Surat — {{ $periode->translatedFormat('F Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            padding: 20mm 20mm 20mm 25mm;
        }

        /* KOP SURAT */
        .kop-wrap {
            display: table;
            width: 100%;
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .kop-logo-cell {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
        }
        .kop-logo-cell img {
            width: 82px;
            height: 82px;
            object-fit: contain;
        }
        .kop-logo-ph {
            width: 82px;
            height: 82px;
            border: 1px solid #ccc;
            display: inline-block;
        }
        .kop-text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-right: 90px;
        }
        .kop-text-cell .b1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; letter-spacing: .05em; line-height: 1.4; }
        .kop-text-cell .b2 { font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.4; }
        .kop-text-cell .b3 { font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: .06em; line-height: 1.3; margin-top: 2px; }
        .kop-text-cell .b4 { font-size: 9pt;  margin-top: 4px; font-style: italic; }

        /* JUDUL LAPORAN */
        .judul-section {
            text-align: center;
            margin: 18px 0 14px;
        }
        .judul-section .judul { font-size: 13pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: .5px; }
        .judul-section .sub-judul { font-size: 11pt; margin-top: 4px; }
        .judul-section .periode-badge {
            display: inline-block;
            margin-top: 6px;
            font-size: 10pt;
            font-weight: bold;
        }

        /* INFO BOX */
        .info-grid {
            display: grid;
            grid-template-columns: 140px 10px 1fr;
            row-gap: 3px;
            font-size: 10.5pt;
            margin-bottom: 16px;
        }
        .info-grid span:nth-child(2) { text-align: center; }

        /* REKAPITULASI */
        .rekap-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 6px;
            text-decoration: underline;
        }

        .rekap-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 18px;
        }
        .rekap-box {
            border: 1px solid #555;
            padding: 8px 10px;
            text-align: center;
        }
        .rekap-box .rek-label { font-size: 9pt; text-transform: uppercase; letter-spacing: .03em; }
        .rekap-box .rek-val { font-size: 18pt; font-weight: bold; line-height: 1.2; margin-top: 2px; }

        /* TABEL RINCIAN */
        .tabel-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 6px;
            text-decoration: underline;
        }

        table.laporan {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 20px;
        }
        table.laporan thead tr {
            background: #ddd;
        }
        table.laporan th {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
        }
        table.laporan td {
            border: 1px solid #555;
            padding: 5px 8px;
            vertical-align: top;
        }
        table.laporan td.center { text-align: center; }
        table.laporan td.bold { font-weight: bold; }

        .pemohon-item {
            padding: 2px 0;
            border-bottom: 1px dotted #bbb;
            font-size: 9.5pt;
        }
        .pemohon-item:last-child { border-bottom: none; }
        .pemohon-status {
            font-size: 8.5pt;
            color: #333;
        }

        /* TANDA TANGAN */
        .ttd-section {
            margin-top: 36px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd-box {
            width: 220px;
            text-align: center;
            font-size: 11pt;
        }
        .ttd-box .ttd-tempat { margin-bottom: 4px; }
        .ttd-box .ttd-jabatan { font-weight: bold; margin-bottom: 64px; }
        .ttd-box .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-box .ttd-nip { font-size: 9.5pt; margin-top: 2px; }

        /* FOOTER */
        .footer-doc {
            margin-top: 24px;
            border-top: 1px solid #aaa;
            padding-top: 6px;
            font-size: 8.5pt;
            color: #555;
            text-align: center;
        }

        @media print {
            body { padding: 15mm 15mm 15mm 20mm; }
            .no-print { display: none !important; }
            table.laporan { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop-wrap">
        <div class="kop-logo-cell">
            <img src="{{ asset('images/logo-desa-karombo.png') }}" alt="Logo Desa Karombo">
        </div>
        <div class="kop-text-cell">
            <div class="b1">{{ $kopSurat->baris_1 }}</div>
            <div class="b2">{{ $kopSurat->baris_2 }}</div>
            <div class="b3">{{ $kopSurat->baris_3 }}</div>
            <div class="b4">{{ $kopSurat->baris_4 }}</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="judul-section">
        <div class="judul">Laporan Bulanan Pengajuan Surat</div>
        <div class="sub-judul">Sistem Informasi Layanan Surat Desa Karombo</div>
        <div class="periode-badge">Periode: {{ $periode->translatedFormat('F Y') }}</div>
    </div>

    {{-- INFO LAPORAN --}}
    <div class="info-grid">
        <span>Nomor</span><span>:</span><span>{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}/LAP-SURAT/{{ $tahun }}</span>
        <span>Periode</span><span>:</span><span>{{ $periode->translatedFormat('F Y') }}</span>
        @if($jenisSuratNama)
        <span>Jenis Surat</span><span>:</span><span><strong>{{ $jenisSuratNama }}</strong></span>
        @else
        <span>Jenis Surat</span><span>:</span><span>Semua Jenis Surat</span>
        @endif
        @if($status)
        <span>Status</span><span>:</span><span>{{ $status }}</span>
        @endif
        @if($nama)
        <span>Pencarian</span><span>:</span><span>"{{ $nama }}"</span>
        @endif
        <span>Tanggal Cetak</span><span>:</span><span>{{ now()->translatedFormat('d F Y') }}</span>
        <span>Dicetak oleh</span><span>:</span><span>Administrator — Sistem Surat Desa Karombo</span>
    </div>

    {{-- TABEL RINCIAN --}}
    <div class="tabel-title">I. Rincian Per Jenis Surat dan Pemohon</div>

    @php $aktif = $laporanPerJenis->where('total', '>', 0)->sortByDesc('total'); @endphp

    @if($aktif->isEmpty())
        <p style="font-style:italic;margin-bottom:20px">Tidak ada data pengajuan surat pada periode ini.</p>
    @else
    <table class="laporan">
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Jenis Surat</th>
                <th style="width:36px">Total</th>
                <th style="width:58px">Disetujui</th>
                <th style="width:58px">Menunggu</th>
                <th style="width:48px">Ditolak</th>
                <th>Daftar Nama Pemohon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aktif as $i => $j)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>
                    <div class="bold">{{ $j->nama_surat }}</div>
                    <div style="font-size:8.5pt;color:#444">{{ $j->kode_surat }}</div>
                </td>
                <td class="center bold">{{ $j->total }}</td>
                <td class="center">{{ $j->disetujui }}</td>
                <td class="center">{{ $j->menunggu }}</td>
                <td class="center">{{ $j->ditolak }}</td>
                <td>
                    @forelse($j->pengajuanSurats as $idx => $p)
                        @php $nama = $p->user->penduduk->nama ?? $p->user->name ?? '-'; @endphp
                        <div class="pemohon-item">
                            {{ $idx + 1 }}. {{ $nama }}
                            <span class="pemohon-status">
                                — {{ $p->status }} ({{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }})
                            </span>
                        </div>
                    @empty
                        <span style="font-size:9pt;color:#999">-</span>
                    @endforelse
                </td>
            </tr>
            @endforeach
            {{-- Baris total --}}
            <tr style="background:#eee">
                <td colspan="2" class="center bold">TOTAL</td>
                <td class="center bold">{{ $summary->total ?? 0 }}</td>
                <td class="center bold">{{ $summary->disetujui ?? 0 }}</td>
                <td class="center bold">{{ $summary->menunggu ?? 0 }}</td>
                <td class="center bold">{{ $summary->ditolak ?? 0 }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- TANDA TANGAN --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-tempat">Karombo, {{ now()->translatedFormat('d F Y') }}</div>
            <div class="ttd-jabatan">Administrator</div>
            <div class="ttd-nama">(_______________________)</div>
            <div class="ttd-nip">NIP. ................................</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer-doc">
        Dokumen ini dicetak dari Sistem Informasi Layanan Surat Desa Karombo &nbsp;|&nbsp; {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

    {{-- Tombol cetak (hilang saat print) --}}
    <div class="no-print" style="position:fixed;bottom:24px;right:24px;display:flex;gap:10px">
        <button onclick="window.print()"
            style="background:#15803d;color:#fff;border:none;border-radius:8px;padding:10px 22px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(0,0,0,.2)">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:8px;padding:10px 16px;font-size:14px;font-weight:600;cursor:pointer">
            ✕ Tutup
        </button>
    </div>

    <script>
        // Auto buka dialog cetak
        window.addEventListener('load', () => setTimeout(() => window.print(), 400));
    </script>
</body>
</html>
