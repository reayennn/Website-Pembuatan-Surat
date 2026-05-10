<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Surat — Desa Karombo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #0f172a;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(16, 185, 129, 0.18), transparent),
                radial-gradient(ellipse 60% 40% at 80% 100%, rgba(59, 130, 246, 0.12), transparent);
        }

        .card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 48px 40px;
            max-width: 580px;
            width: 100%;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        }

        /* LOGO / BADGE */
        .badge-wrap { text-align: center; margin-bottom: 28px; }
        .badge-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            font-size: 32px;
            margin-bottom: 12px;
        }
        .badge-icon.valid   { background: rgba(16,185,129,0.15); border: 2px solid rgba(16,185,129,0.4); }
        .badge-icon.invalid { background: rgba(239,68,68,0.15);  border: 2px solid rgba(239,68,68,0.4);  }

        .status-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .status-text.valid   { color: #10b981; }
        .status-text.invalid { color: #ef4444; }

        .status-sub {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            margin-top: 6px;
        }

        /* DIVIDER */
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 28px 0;
        }

        /* DATA TABLE */
        .data-grid { display: flex; flex-direction: column; gap: 14px; }
        .data-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
        }
        .data-label {
            color: rgba(255,255,255,0.45);
            flex-shrink: 0;
            width: 160px;
        }
        .data-value {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            text-align: right;
        }
        .data-value.highlight {
            color: #10b981;
            font-weight: 600;
        }

        /* FOOTER */
        .footer-note {
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            margin-top: 28px;
            line-height: 1.6;
        }
        .footer-note strong { color: rgba(255,255,255,0.5); }

        /* PULSE ANIMATION */
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
            70%  { box-shadow: 0 0 0 16px rgba(16,185,129,0); }
            100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
        }
        .badge-icon.valid { animation: pulse-ring 2s infinite; }
    </style>
</head>
<body>
    <div class="card">

        @if($surat)
            {{-- SURAT VALID --}}
            @php
                $pengajuan = $surat->pengajuanSurat;
                $penduduk  = $pengajuan->user->penduduk;
                $dp        = $pengajuan->data_pemohon ?? [];
                $nama      = $dp['nama'] ?? ($penduduk?->nama ?? $pengajuan->user->name ?? '-');
                $nik       = $dp['nik']  ?? ($penduduk?->nik  ?? '-');
            @endphp

            <div class="badge-wrap">
                <div class="badge-icon valid" style="font-size: 1rem; font-weight: bold;">✔</div>
                <div class="status-text valid">Surat Terverifikasi</div>
                <div class="status-sub">Surat ini asli dan sah dikeluarkan oleh Desa Karombo</div>
            </div>

            <hr class="divider">

            <div class="data-grid">
                <div class="data-row">
                    <span class="data-label">Jenis Surat</span>
                    <span class="data-value highlight">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Nomor Surat</span>
                    <span class="data-value">{{ $surat->nomor_surat }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Tanggal Diterbitkan</span>
                    <span class="data-value">
                        {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">Nama Pemohon</span>
                    <span class="data-value">{{ $nama }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">NIK</span>
                    <span class="data-value">{{ $nik }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Diterbitkan oleh</span>
                    <span class="data-value">Kepala Desa Karombo</span>
                </div>
            </div>

            <p class="footer-note">
                Verifikasi dilakukan secara digital melalui sistem<br>
                <strong>Sistem Pelayanan Surat Desa Karombo</strong>
            </p>

        @else
            {{-- SURAT TIDAK DITEMUKAN --}}
            <div class="badge-wrap">
                <div class="badge-icon invalid" style="font-size: 1rem; font-weight: bold;">✕</div>
                <div class="status-text invalid">Surat Tidak Ditemukan</div>
                <div class="status-sub">QR Code ini tidak terdaftar dalam sistem atau surat telah dicabut</div>
            </div>

            <hr class="divider">

            <p style="text-align:center; color:rgba(255,255,255,0.4); font-size:14px; line-height:1.7;">
                Kemungkinan surat ini <strong style="color:rgba(239,68,68,0.8)">tidak resmi</strong>
                atau dicetak dari sumber yang tidak sah.<br>
                Silakan hubungi Kantor Desa Karombo untuk konfirmasi lebih lanjut.
            </p>

            <p class="footer-note" style="margin-top: 20px;">
                <strong>Kantor Desa Karombo</strong><br>
                Kecamatan Pekat, Kabupaten Dompu, NTB
            </p>
        @endif

    </div>
</body>
</html>
