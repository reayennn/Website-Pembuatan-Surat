<x-app-layout>
    <x-slot name="header">Lihat Data Pengajuan</x-slot>

    {{-- Gradient Header --}}
    <div style="box-shadow:0 1px 4px rgba(0,0,0,.08);background:#15803d;border-radius:16px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden">
        <div style="position:absolute;inset:0;opacity:.06;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Crect x='10' y='10' width='60' height='60' fill='none' stroke='white' stroke-width='1'/%3E%3C/svg%3E\");background-size:80px"></div>
        <div style="position:relative;display:flex;align-items:flex-start;justify-content:space-between">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
                    <svg width="22" height="22" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h2 style="color:#fff;font-size:1.5rem;font-weight:800">Riwayat Pengajuan Saya</h2>
                </div>
                <p style="color:rgba(255,255,255,.65);font-size:.83rem;margin-bottom:10px">Daftar seluruh pengajuan surat yang pernah dibuat</p>
                <div style="color:rgba(255,255,255,.5);font-size:.75rem">
                    <a href="{{ route('dashboard') }}" style="color:rgba(255,255,255,.7);text-decoration:none">Dashboard</a>
                    <span style="margin:0 6px">/</span>
                    <span style="color:rgba(255,255,255,.85)">Lihat Data</span>
                </div>
            </div>
            @if(Auth::user()->penduduk_id)
                <a href="{{ route('pengajuan.create') }}" class="btn" style="background:#fff;color:#15803d;font-weight:700;flex-shrink:0">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Pengajuan
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6">
            <h3 style="font-weight:700;color:#111827;font-size:.95rem">List Surat</h3>
        </div>

        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Nama Surat</th>
                        <th>Status</th>
                        <th>Waktu Input</th>
                        <th style="text-align:center;width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $i => $p)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $pengajuans->firstItem() + $i }}</td>
                            <td style="font-weight:600;color:#111827">{{ $p->jenisSurat->nama_surat }}</td>
                            <td>
                                @if($p->status == 'Menunggu')
                                    <span class="badge-yellow"><span class="badge-dot" style="background:#d97706"></span>Menunggu Proses</span>
                                @elseif($p->status == 'Menunggu Kades')
                                    <span class="badge-blue" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe"><span class="badge-dot" style="background:#2563eb"></span>Menunggu Kades</span>
                                @elseif($p->status == 'Disetujui')
                                    <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Disetujui</span>
                                @elseif($p->status == 'Perbaikan')
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;border-radius:99px;font-size:.72rem;font-weight:700">
                                        <span style="width:6px;height:6px;background:#f97316;border-radius:50%"></span>Perlu Perbaikan
                                    </span>
                                @else
                                    <span class="badge-red"><span class="badge-dot" style="background:#dc2626"></span>Ditolak</span>
                                    @if($p->keterangan)
                                        <div style="font-size:0.7rem;color:#dc2626;margin-top:4px;max-width:180px;white-space:normal;line-height:1.2">
                                            Alasan: {{ Str::limit($p->keterangan, 50) }}
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:#6b7280;white-space:nowrap">
                                {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d-m-Y H:i') }}
                            </td>
                            <td style="text-align:center">
                                <div style="display:flex;gap:6px;align-items:center;justify-content:center">
                                    <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-primary">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                    @if($p->status == 'Perbaikan')
                                        <a href="{{ route('pengajuan.edit', $p->id) }}" class="btn btn-sm" style="background:#d97706;color:#fff;border-color:#d97706">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Perbaiki
                                        </a>
                                    @endif
                                    @if($p->status == 'Disetujui' && $p->surat)
                                        <a href="{{ Storage::url($p->surat->file_pdf) }}" target="_blank"
                                           class="btn btn-sm btn-primary">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Unduh PDF
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:60px 0">
                                <svg width="40" height="40" fill="none" stroke="#d1d5db" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p style="font-weight:600;color:#6b7280;margin-bottom:6px">Belum ada pengajuan surat</p>
                                <a href="{{ route('pengajuan.create') }}" class="btn btn-sm" style="background:linear-gradient(135deg,#2563eb,#4f46e5);color:#fff">Buat Pengajuan Pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:#6b7280">
                <span>Showing {{ $pengajuans->firstItem() }} to {{ $pengajuans->lastItem() }} of {{ $pengajuans->total() }} entries</span>
                {{ $pengajuans->links() }}
            </div>
        @else
            <div style="padding:10px 18px;border-top:1px solid #f3f4f6;font-size:.75rem;color:#9ca3af">Menampilkan {{ $pengajuans->count() }} data</div>
        @endif
    </div>

</x-app-layout>
