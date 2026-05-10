<x-app-layout>
    <x-slot name="header">Detail Pengajuan</x-slot>

    {{-- Gradient Header --}}
    <div style="box-shadow:0 1px 4px rgba(0,0,0,.08);background:#15803d;border-radius:16px;padding:24px 32px;margin-bottom:24px;position:relative;overflow:hidden">
        <div style="position:relative;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
                    <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h2 style="color:#fff;font-size:1.3rem;font-weight:800">Detail Pengajuan Surat</h2>
                </div>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem">
                    <a href="{{ route('dashboard') }}" style="color:rgba(255,255,255,.7);text-decoration:none">Dashboard</a>
                    <span style="margin:0 6px">/</span>
                    <a href="{{ route('pengajuan.index') }}" style="color:rgba(255,255,255,.7);text-decoration:none">Lihat Data</a>
                    <span style="margin:0 6px">/</span>
                    <span style="color:rgba(255,255,255,.85)">Detail</span>
                </div>
            </div>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border-color:rgba(255,255,255,.2);flex-shrink:0">
                ← Kembali Ke Semua Data
            </a>
        </div>
    </div>

    <div style="max-width:800px">

        {{-- Data Surat --}}
        <div class="g-card" style="margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="padding:14px 20px;border-bottom:2px solid #16a34a;background:linear-gradient(135deg,#f0fdf4,#ecfdf5)">
                <h3 style="font-weight:700;color:#14532d;font-size:.95rem">Data Surat</h3>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:14px">

                <div style="display:grid;grid-template-columns:180px 1fr;gap:4px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                    <p style="font-size:.78rem;font-weight:700;color:#6b7280">Nama Surat</p>
                    <p style="font-size:.88rem;color:#111827;font-weight:600">{{ $pengajuan->jenisSurat->nama_surat }}</p>
                </div>

                <div style="display:grid;grid-template-columns:180px 1fr;gap:4px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                    <p style="font-size:.78rem;font-weight:700;color:#6b7280">Keterangan Surat</p>
                    <p style="font-size:.85rem;color:#374151;line-height:1.5">
                        @if($pengajuan->jenisSurat->template && $pengajuan->jenisSurat->template->keterangan)
                            {{ $pengajuan->jenisSurat->template->keterangan }}
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div style="display:grid;grid-template-columns:180px 1fr;gap:4px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                    <p style="font-size:.78rem;font-weight:700;color:#6b7280">Persyaratan Surat</p>
                    <div style="font-size:.85rem;color:#374151;line-height:1.6">
                        @if($pengajuan->jenisSurat->template && $pengajuan->jenisSurat->template->persyaratan)
                            @foreach(explode("\n", $pengajuan->jenisSurat->template->persyaratan) as $line)
                                @if(trim($line))
                                    <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:2px">
                                        <span style="color:#16a34a;font-weight:700;flex-shrink:0">✓</span>
                                        <span>{{ trim($line) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <span style="color:#9ca3af">Tidak ada persyaratan khusus</span>
                        @endif
                    </div>
                </div>


                <div style="display:grid;grid-template-columns:180px 1fr;gap:4px">
                    <p style="font-size:.78rem;font-weight:700;color:#6b7280">Status</p>
                    <div>
                        @if($pengajuan->status == 'Menunggu')
                            <span class="badge-yellow" style="font-size:.8rem;padding:5px 14px"><span class="badge-dot" style="background:#d97706"></span>Menunggu Proses Admin</span>
                        @elseif($pengajuan->status == 'Menunggu Kades')
                            <span class="badge-blue" style="font-size:.8rem;padding:5px 14px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe"><span class="badge-dot" style="background:#2563eb"></span>Menunggu TTD Kepala Desa</span>
                        @elseif($pengajuan->status == 'Disetujui')
                            <span class="badge-green" style="font-size:.8rem;padding:5px 14px"><span class="badge-dot" style="background:#16a34a"></span>Selesai / Disetujui</span>
                        @else
                            <span class="badge-red" style="font-size:.8rem;padding:5px 14px"><span class="badge-dot" style="background:#dc2626"></span>Ditolak</span>
                        @endif
                        
                        @if($pengajuan->status == 'Ditolak' && $pengajuan->keterangan)
                            <div style="margin-top:12px;background:#fef2f2;border-left:3px solid #ef4444;padding:10px 14px;border-radius:0 8px 8px 0">
                                <p style="font-size:.78rem;font-weight:700;color:#991b1b;margin-bottom:2px">Alasan Penolakan:</p>
                                <p style="font-size:.85rem;color:#b91c1c;line-height:1.4">{{ $pengajuan->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Data Pemohon --}}
        <div class="g-card" style="margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="padding:14px 20px;border-bottom:2px solid #16a34a;background:linear-gradient(135deg,#f0fdf4,#ecfdf5)">
                <h3 style="font-weight:700;color:#14532d;font-size:.95rem">Data Pengajuan</h3>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:0">
                @php
                    $penduduk = $pengajuan->user->penduduk;
                    $dp = $pengajuan->data_pemohon ?? [];

                    $fields = [
                        'NIK'              => $dp['nik']          ?? ($penduduk?->nik          ?? '-'),
                        'Nama'             => $dp['nama']         ?? ($penduduk?->nama         ?? '-'),
                        'Tempat Lahir'     => $dp['tempat_lahir'] ?? ($penduduk?->tempat_lahir ?? '-'),
                        'Tanggal Lahir'    => isset($dp['tanggal_lahir'])
                            ? \Carbon\Carbon::parse($dp['tanggal_lahir'])->translatedFormat('d F Y')
                            : ($penduduk?->tanggal_lahir
                                ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y')
                                : '-'),
                        'Jenis Kelamin'    => $dp['jenis_kelamin'] ?? ($penduduk?->jenis_kelamin ?? '-'),
                        'Agama'            => $dp['agama']         ?? ($penduduk?->agama         ?? '-'),
                        'Pekerjaan'        => $dp['pekerjaan']     ?? ($penduduk?->pekerjaan     ?? '-'),
                        'Alamat'           => $dp['alamat']        ?? ($penduduk?->alamat        ?? '-'),
                        'Tanggal Pengajuan'=> \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y, H:i'),
                        'File Persyaratan' => $pengajuan->file_persyaratan
                            ? '<a href="'.Storage::url($pengajuan->file_persyaratan).'" target="_blank" style="color:#16a34a;text-decoration:underline;display:inline-flex;align-items:center;gap:4px"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>Lihat Dokumen</a>'
                            : '<span style="color:#9ca3af;font-style:italic">Tidak ada file</span>',
                    ];
                @endphp

                @foreach($fields as $label => $val)
                    <div style="display:grid;grid-template-columns:180px 1fr;gap:4px;padding:11px 0;border-bottom:1px solid #f3f4f6">
                        <p style="font-size:.78rem;font-weight:700;color:#6b7280">{{ $label }}</p>
                        <p style="font-size:.85rem;color:#111827;font-weight:{{ in_array($label,['NIK','Nama']) ? '600' : '400' }}">{!! $val !!}</p>
                    </div>
                @endforeach

                @if($pengajuan->data_pemohon)
                    <p style="font-size:.7rem;color:#9ca3af;margin-top:8px;font-style:italic">* Data sesuai yang diisi pemohon saat pengajuan</p>
                @endif

                @if($pengajuan->jenis_surat_id == 4)
                    <div style="margin-top:16px; border-top:1px dashed #d1d5db; padding-top:16px;">
                        <h4 style="font-weight:700; color:#374151; font-size:.85rem; margin-bottom:8px;">Data Kematian & Pelapor</h4>
                        @foreach([
                            'Hari Meninggal' => $dp['kematian_hari'] ?? '-',
                            'Tanggal Meninggal' => isset($dp['kematian_tanggal']) ? \Carbon\Carbon::parse($dp['kematian_tanggal'])->translatedFormat('d F Y') : '-',
                            'Tempat Meninggal' => $dp['kematian_tempat'] ?? '-',
                            'Penyebab Kematian' => $dp['kematian_penyebab'] ?? '-',
                            'Nama Pelapor' => $dp['pelapor_nama'] ?? '-',
                            'NIK Pelapor' => $dp['pelapor_nik'] ?? '-',
                            'TTL / Umur Pelapor' => $dp['pelapor_ttl'] ?? '-',
                            'Pekerjaan Pelapor' => $dp['pelapor_pekerjaan'] ?? '-',
                            'Alamat Pelapor' => $dp['pelapor_alamat'] ?? '-',
                            'Hubungan Pelapor' => $dp['pelapor_hubungan'] ?? '-'
                        ] as $lbl => $v)
                            <div style="display:grid;grid-template-columns:180px 1fr;gap:4px;padding:6px 0;border-bottom:1px solid #f3f4f6">
                                <p style="font-size:.78rem;font-weight:700;color:#6b7280">{{ $lbl }}</p>
                                <p style="font-size:.85rem;color:#111827;">{{ $v }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Download (jika disetujui) --}}
        @if($pengajuan->status == 'Disetujui' && $pengajuan->surat)
            <div class="g-card" style="padding:20px;display:flex;align-items:center;justify-content:space-between;border:2px solid #86efac;background:#f0fdf4;box-shadow:0 1px 4px rgba(0,0,0,.07)">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:40px;height:40px;background:#16a34a;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p style="font-weight:700;color:#15803d">Surat Telah Diterbitkan</p>
                        <p style="font-size:.78rem;color:#166534">Klik tombol di kanan untuk mengunduh surat Anda</p>
                    </div>
                </div>
                <a href="{{ Storage::url($pengajuan->surat->file_pdf) }}" target="_blank"
                   class="btn btn-primary" style="background:#15803d;flex-shrink:0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Unduh Surat PDF
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
