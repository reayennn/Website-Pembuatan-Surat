<x-app-layout>
    <x-slot name="header">Dashboard Kepala Desa</x-slot>

    {{-- Gradient Header --}}
    <div style="box-shadow:0 1px 4px rgba(0,0,0,.08);background:#15803d;border-radius:16px;overflow:hidden;margin-bottom:24px;position:relative">
        <div style="position:absolute;inset:0;opacity:.07;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Ccircle cx='50' cy='50' r='40' stroke='white' stroke-width='1' fill='none'/%3E%3C/svg%3E\");background-size:100px"></div>
        <div style="padding:28px 32px;position:relative">
            <p style="color:rgba(255,255,255,.65);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Sistem Layanan Surat · Desa Karombo</p>
            <h1 style="color:#fff;font-size:1.6rem;font-weight:800;letter-spacing:-.02em;margin-bottom:8px">
                Selamat Datang, Bapak Kepala Desa
            </h1>
            <p style="color:rgba(255,255,255,.8);font-size:.85rem;max-width:500px;line-height:1.5">
                Pantau statistik surat dan setujui permohonan surat dari masyarakat dengan cepat dan praktis.
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px;margin-bottom:24px">
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07);padding:24px;border-bottom:3px solid #3b82f6">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <h3 style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Tunggu Persetujuan</h3>
                    <p style="font-size:2rem;font-weight:800;color:#1e40af;margin-top:4px">{{ $stats['perlu_ttd'] ?? 0 }}</p>
                </div>
                <div style="width:42px;height:42px;border-radius:12px;background:#eff6ff;display:flex;align-items:center;justify-content:center;color:#3b82f6">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
            </div>
            <a href="{{ route('kades.persetujuan.index') }}" style="display:inline-block;margin-top:12px;font-size:.78rem;font-weight:600;color:#3b82f6;text-decoration:none">Lihat Detail →</a>
        </div>

        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07);padding:24px;border-bottom:3px solid #10b981">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <h3 style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Surat Disetujui</h3>
                    <p style="font-size:2rem;font-weight:800;color:#065f46;margin-top:4px">{{ $stats['disetujui'] ?? 0 }}</p>
                </div>
                <div style="width:42px;height:42px;border-radius:12px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;color:#10b981">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div style="margin-top:12px;font-size:.78rem;color:#6b7280">Total surat yang telah Anda TTD</div>
        </div>
        
        <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07);padding:24px;border-bottom:3px solid #ef4444">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <h3 style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Surat Ditolak</h3>
                    <p style="font-size:2rem;font-weight:800;color:#b91c1c;margin-top:4px">{{ $stats['ditolak'] ?? 0 }}</p>
                </div>
                <div style="width:42px;height:42px;border-radius:12px;background:#fef2f2;display:flex;align-items:center;justify-content:center;color:#ef4444">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div style="margin-top:12px;font-size:.78rem;color:#6b7280">Total surat yang Anda tolak</div>
        </div>
    </div>

    {{-- Tabel Pengajuan Terbaru (Butuh Persetujuan) --}}
    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center">
            <h3 style="font-weight:700;color:#111827;font-size:.95rem">Butuh Persetujuan Anda</h3>
            <a href="{{ route('kades.persetujuan.index') }}" style="font-size:.8rem;color:#3b82f6;font-weight:600;text-decoration:none">Lihat Semua</a>
        </div>
        
        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th style="text-align:center;width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPengajuan as $i => $p)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $i + 1 }}</td>
                            <td style="font-size:.8rem;color:#6b7280;white-space:nowrap">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}</td>
                            <td>
                                <div style="font-weight:600;color:#111827">{{ $p->user->penduduk ? $p->user->penduduk->nama : $p->user->name }}</div>
                            </td>
                            <td style="font-size:.85rem;font-weight:500;color:#374151">{{ $p->jenisSurat->nama_surat }}</td>
                            <td style="text-align:center">
                                <a href="{{ route('kades.persetujuan.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Cek Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px 0;color:#9ca3af">
                                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p style="font-weight:500;color:#6b7280">Yeay, semua surat sudah Anda tandatangani!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
