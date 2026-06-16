<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $pengajuan->jenisSurat->nama_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 1cm 2cm 1.5cm 2cm;
            color: #000;
        }

        /* ── KOP SURAT ── */
        .kop-wrap {
            display: table;
            width: 100%;
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 24px;
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
        .kop-text-cell .b1 { font-size: 12pt;  font-weight: bold; text-transform: uppercase; letter-spacing: .05em; line-height: 1.4; }
        .kop-text-cell .b2 { font-size: 12pt;  font-weight: bold; text-transform: uppercase; line-height: 1.4; }
        .kop-text-cell .b3 { font-size: 18pt;  font-weight: bold; text-transform: uppercase; letter-spacing: .06em; line-height: 1.3; margin-top: 2px; }
        .kop-text-cell .b4 { font-size: 9pt;   margin-top: 4px; font-style: italic; }

        /* ── JUDUL ── */
        .judul-surat {
            text-align: center;
            margin: 16px 0 14px;
        }
        .judul-surat .nama-surat {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .judul-surat .nomor-surat {
            font-size: 11pt;
            margin-top: 5px;
        }

        /* ── ISI ── */
        .isi-surat {
            text-align: justify;
            margin-bottom: 10px;
        }
        .isi-surat p { margin-bottom: 6px; line-height: 1.6; }
        .data-table { width: 100%; border-collapse: collapse; margin: 12px 0 12px 18px; }
        .data-table td { padding: 4px 6px; vertical-align: top; }
        .data-table td:first-child { width: 195px; }
        .data-table td:nth-child(2) { width: 16px; }

        /* ── TANDA TANGAN + QR CODE ── */
        .ttd-block {
            float: right;
            text-align: center;
            margin-top: 14px;
            width: 180px;
        }
        .ttd-block p { margin: 0; line-height: 1.4; }
        .ttd-block .nama-kades {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
            margin-top: 2px;
        }
        .ttd-block .nipd-kades { font-size: 9pt; margin-top: 1px; }
        .ttd-block .qr-img {
            width: 72px;
            height: 72px;
            display: block;
            margin: 4px auto 0;
        }
        .ttd-block .qr-label {
            font-size: 6.5pt;
            color: #555;
            margin-top: 1px;
        }
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <!-- KOP SURAT: Logo kiri, teks tengah -->
    <div class="kop-wrap">
        <div class="kop-logo-cell">
            @if($kopSurat->logo)
                <img src="{{ storage_path('app/public/' . $kopSurat->logo) }}" alt="Logo">
            @else
                <div class="kop-logo-ph"></div>
            @endif
        </div>
        <div class="kop-text-cell">
            <div class="b1">{{ $kopSurat->baris_1 }}</div>
            <div class="b2">{{ $kopSurat->baris_2 }}</div>
            <div class="b3">{{ $kopSurat->baris_3 }}</div>
            <div class="b4">{{ $kopSurat->baris_4 }}</div>
        </div>
    </div>

    <!-- JUDUL SURAT -->
    @php
        $templateRecord = \App\Models\TemplateSurat::where('jenis_surat_id', $pengajuan->jenis_surat_id)
                            ->where('status', 'Aktif')->first();
        $judulTampil    = $templateRecord?->judul_surat ?: strtoupper($pengajuan->jenisSurat->nama_surat);

        // Data masyarakat
        $p           = $pengajuan->user->penduduk;
        $dp          = $pengajuan->data_pemohon ?? [];
        $nama        = $dp['nama']              ?? ($p?->nama             ?? '-');
        $nik         = $dp['nik']               ?? ($p?->nik              ?? '-');
        $tempatLahir = $dp['tempat_lahir']      ?? ($p?->tempat_lahir     ?? '-');
        $tglLahir    = $dp['tanggal_lahir']     ?? ($p?->tanggal_lahir    ?? null);
        $jk          = $dp['jenis_kelamin']     ?? ($p?->jenis_kelamin    ?? '-');
        $statusKawin = $dp['status_perkawinan'] ?? ($p?->status_perkawinan ?? 'Belum Kawin');
        $kwn         = $dp['kewarganegaraan']   ?? ($p?->kewarganegaraan  ?? 'WNI');
        $agama       = $dp['agama']             ?? ($p?->agama            ?? '-');
        $pekerjaan   = $dp['pekerjaan']         ?? ($p?->pekerjaan        ?? '-');
        $alamat      = $dp['alamat']            ?? ($p?->alamat           ?? '-');
        $tglFormatted = $tglLahir ? \Carbon\Carbon::parse($tglLahir)->translatedFormat('d F Y') : '-';

        // ── Fungsi pembersih keperluan dari konten template PDF ──
        // Menghapus semua variasi placeholder keperluan beserta teks pendahulunya
        $bersihkanKeperluan = function(string $html): string {
            // Hapus placeholder dengan variasi penulisan
            $patterns = [
                // Hapus klausa "untuk keperluan: {placeholder}" dan variasinya
                '/[Uu]ntuk\s+keperluan\s*[:\-]?\s*(\{[^}]*\}|\{\$keperluan\}|\{\$purpose\})?/u',
                '/[Kk]eperluan\s*[:\-]\s*(\{[^}]*\}|\{\$keperluan\}|\{\$purpose\})?/u',
                // Hapus sisa placeholder yang masih ada
                '/\{\s*\$keperluan\s*\}/u',
                '/\{\s*\$purpose\s*\}/u',
                '/\{\s*keperluan\s*\}/u',
                '/\{\s*purpose\s*\}/u',
                '/\{\s*\}/u',
            ];
            foreach ($patterns as $pattern) {
                $html = preg_replace($pattern, '', $html);
            }
            // Bersihkan spasi ganda dan titik/koma yang tersisa
            $html = preg_replace('/\s{2,}/', ' ', $html);
            $html = preg_replace('/:\s*\./', '.', $html);
            return trim($html);
        };

        $isiPembuka = $bersihkanKeperluan($templateRecord?->isi_pembuka ?? '');
        $isiPenutup = $bersihkanKeperluan($templateRecord?->isi_penutup ?? '');
    @endphp
    <div class="judul-surat">
        <div class="nama-surat">{{ $judulTampil }}</div>
        <div class="nomor-surat">Nomor : {{ $nomorSurat }}</div>
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat">
        @if($pengajuan->jenis_surat_id == 4)
            {{-- FORMAT KHUSUS SURAT KEMATIAN --}}
            <p>Yang bertanda tangan di bawah ini Kepala {{ \Str::title(strtolower(str_replace('KECAMATAN', 'Kecamatan', $kopSurat->baris_3))) ?? 'Desa Karombo' }} Kecamatan {{ \Str::title(strtolower(str_replace('KECAMATAN', '', $kopSurat->baris_2))) ?? 'Pekat' }} Kabupaten Dompu Menerangkan Bahwa :</p>

            @php $dp = $pengajuan->data_pemohon ?? []; @endphp
            <table class="data-table" style="margin-top:16px;">
                <colgroup><col style="width:180px"><col style="width:16px"><col></colgroup>
                <tr><td>Nama Lengkap</td><td style="padding:0 6px">:</td><td>{{ $dp['nama'] ?? $nama }}</td></tr>
                <tr><td>Nomor KTP</td><td style="padding:0 6px">:</td><td>{{ $dp['nik'] ?? $nik }}</td></tr>
                <tr><td>Jenis Kelamin</td><td style="padding:0 6px">:</td><td>{{ $dp['jenis_kelamin'] ?? $jk }}</td></tr>
                <tr><td>Tempat Tgl lahir/umur</td><td style="padding:0 6px">:</td><td>{{ $dp['tempat_lahir'] ?? $tempatLahir }}, {{ $tglFormatted }}</td></tr>
                <tr><td>Agama</td><td style="padding:0 6px">:</td><td>{{ $dp['agama'] ?? $agama }}</td></tr>
                <tr><td>Alamat</td><td style="padding:0 6px">:</td><td>{{ $dp['alamat'] ?? $alamat }}</td></tr>
            </table>

            <p style="font-weight:bold; margin-top:20px; margin-bottom:10px;">Telah Meninggal Dunia pada:</p>
            <table class="data-table">
                <colgroup><col style="width:180px"><col style="width:16px"><col></colgroup>
                <tr><td>Hari</td><td style="padding:0 6px">:</td><td>{{ $dp['kematian_hari'] ?? '-' }}</td></tr>
                <tr><td>Tanggal</td><td style="padding:0 6px">:</td><td>{{ isset($dp['kematian_tanggal']) ? \Carbon\Carbon::parse($dp['kematian_tanggal'])->translatedFormat('d F Y') : '-' }}</td></tr>
                <tr><td>Bertempat</td><td style="padding:0 6px">:</td><td>{{ $dp['kematian_tempat'] ?? '-' }}</td></tr>
            </table>

            <p style="margin-top:24px; font-weight:bold;">Penyebab Kematian <span style="display:inline-block;width:40px;"></span>: <span style="font-weight:normal;">{{ $dp['kematian_penyebab'] ?? '-' }}</span></p>

            <p style="margin-top:24px;">Surat Kematian ini dibuat berdasarkan keterangan pelapor :</p>
            <table class="data-table" style="margin-top:10px;">
                <colgroup><col style="width:180px"><col style="width:16px"><col></colgroup>
                <tr><td>Nama Lengkap</td><td style="padding:0 6px">:</td><td>{{ $dp['pelapor_nama'] ?? '-' }}</td></tr>
                <tr><td>Nomor KTP</td><td style="padding:0 6px">:</td><td>{{ $dp['pelapor_nik'] ?? '-' }}</td></tr>
                <tr><td>Tempat Tgl lahir/Umur</td><td style="padding:0 6px">:</td><td>{{ $dp['pelapor_ttl'] ?? '-' }}</td></tr>
                <tr><td>Pekerjaan</td><td style="padding:0 6px">:</td><td>{{ $dp['pelapor_pekerjaan'] ?? '-' }}</td></tr>
                <tr><td>Alamat</td><td style="padding:0 6px">:</td><td>{{ $dp['pelapor_alamat'] ?? '-' }}</td></tr>
            </table>

            <p style="margin-top:24px; font-weight:bold;">Hubungan Pelapor Dengan Yang Meninggal Dunia <span style="display:inline-block;width:10px;"></span>: <span style="font-weight:normal;">{{ $dp['pelapor_hubungan'] ?? '-' }}</span></p>

        @else
            {{-- FORMAT STANDAR --}}
            {{-- PEMBUKA -- dari template atau fallback --}}
            @if($isiPembuka)
                {!! $isiPembuka !!}
            @elseif($isiSurat)
                {{-- Legacy: template lama masih pakai isi_surat --}}
                {!! $isiSurat !!}
            @else
                <p>Yang bertanda tangan di bawah ini Kepala {{ \Str::title(strtolower($kopSurat->baris_3)) }} {{ \Str::title(strtolower($kopSurat->baris_2)) }} Kabupaten Dompu menerangkan dengan sebenar &#8209; sebenarnya kepada :</p>
            @endif

            {{-- TABEL DATA MASYARAKAT — SELALU OTOMATIS DARI DATA WARGA --}}
            @if($templateRecord?->isi_pembuka || $templateRecord?->isi_penutup)
            <table class="data-table">
                <colgroup>
                    <col style="width:200px">
                    <col style="width:16px">
                    <col>
                </colgroup>
                <tr>
                    <td>N a m a</td>
                    <td style="padding:0 6px">:</td>
                    <td><strong>{{ $nama }}</strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $nik }}</td>
                </tr>
                <tr>
                    <td>Tempat / Tanggal Lahir</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $tempatLahir }} / {{ $tglFormatted }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $jk }}</td>
                </tr>
                <tr>
                    <td>Kewarganegaraan</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $kwn === 'WNI' ? 'Indonesia' : $kwn }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $agama }}</td>
                </tr>
                <tr>
                    <td>Status Perkawinan</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $statusKawin }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $pekerjaan }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td style="padding:0 6px">:</td>
                    <td>{{ $alamat }}</td>
                </tr>
            </table>
            @endif

            {{-- PENUTUP -- dari template atau fallback --}}
            @if($isiPenutup)
                {!! $isiPenutup !!}
            @elseif(!$isiPembuka && !$isiSurat)
                <p>Bahwa yang tersebut namanya di atas adalah benar-benar warga / penduduk Asli {{ \Str::title(strtolower($kopSurat->baris_3)) }} {{ \Str::title(strtolower($kopSurat->baris_2)) }} Kabupaten Dompu dan sampai saat surat ini dikeluarkan yang bersangkutan masih Berdomisili di {{ \Str::title(strtolower($kopSurat->baris_3)) }} {{ \Str::title(strtolower($kopSurat->baris_2)) }} Kabupaten Dompu.</p>
                <p style="margin-top:14px">Demikian surat keterangan ini kami berikan untuk dipergunakan sebagaimana mestinya.</p>
            @endif
        @endif
    </div>

    <!-- TANDA TANGAN + QR CODE -->
    <div class="ttd-block">
        <p>Karombo, {{ $tanggalSurat->translatedFormat('d F Y') }}</p>
        <p>Kepala {{ ucwords(strtolower($kopSurat->baris_3)) }},</p>

        @if($tandaTangan->gambar_qr)
            <img class="qr-img" src="{{ storage_path('app/public/' . $tandaTangan->gambar_qr) }}" alt="QR TTD">
        @elseif(isset($qrCodeBase64))
            <img class="qr-img" src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Verifikasi">
        @else
            <div style="height: 65px;"></div>
        @endif

        <p class="nama-kades">{{ $tandaTangan->nama }}</p>
        @if($tandaTangan->nipd)
            <p class="nipd-kades">NIPD. {{ $tandaTangan->nipd }}</p>
        @endif
    </div>
    <div class="clearfix"></div>

</body>
</html>
