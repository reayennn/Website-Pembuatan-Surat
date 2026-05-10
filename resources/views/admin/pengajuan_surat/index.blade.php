<x-app-layout>
    <x-slot name="header">Daftar Pengajuan Surat</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert pengajuan belum diverifikasi --}}
    @php
        $jumlahMenunggu = \App\Models\PengajuanSurat::where('status', 'Menunggu')->count();
    @endphp
    @if($jumlahMenunggu > 0)
        <div style="display:flex;align-items:center;gap:12px;background:#fffbeb;border:1.5px solid #fde68a;
                    color:#92400e;padding:12px 16px;border-radius:10px;font-size:.83rem;
                    font-weight:500;margin-bottom:20px">
            <span style="flex-shrink:0;width:36px;height:36px;background:#fef3c7;border-radius:50%;
                         display:flex;align-items:center;justify-content:center">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </span>
            <div style="flex:1">
                <span style="font-weight:700">Perhatian!</span>
                Terdapat <strong style="color:#b45309">{{ $jumlahMenunggu }} pengajuan surat</strong>
                yang menunggu verifikasi Anda.
            </div>
            <a href="{{ route('admin.pengajuan_surat.index', ['status' => 'Menunggu']) }}"
               style="flex-shrink:0;padding:6px 14px;background:#d97706;color:#fff;border-radius:7px;
                      font-size:.78rem;font-weight:700;text-decoration:none;white-space:nowrap">
                Lihat Sekarang →
            </a>
        </div>
    @endif

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div class="g-card-header">
            <div>
                <h3>Daftar Pengajuan Masuk</h3>
                <p>Verifikasi dan kelola semua permohonan surat dari masyarakat</p>
            </div>
        </div>

        {{-- ===== FORM PENCARIAN ===== --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f3f4f6">
            <form method="GET" action="{{ route('admin.pengajuan_surat.index') }}"
                  style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">

                {{-- Input Pencarian --}}
                <div style="position:relative;flex:1;min-width:200px">
                    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama, NIK pemohon atau jenis surat..."
                           style="width:100%;padding:8px 12px 8px 34px;border:1px solid #e5e7eb;border-radius:8px;
                                  font-size:.82rem;outline:none;transition:border .2s;box-sizing:border-box"
                           onfocus="this.style.borderColor='#166534'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                {{-- Filter Status --}}
                <select name="status"
                        style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:.82rem;
                               outline:none;background:#fff;cursor:pointer;min-width:160px"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Menunggu"        {{ $status == 'Menunggu'        ? 'selected' : '' }}>Menunggu Proses</option>
                    <option value="Menunggu Kades"  {{ $status == 'Menunggu Kades'  ? 'selected' : '' }}>Menunggu TTD Kades</option>
                    <option value="Disetujui"       {{ $status == 'Disetujui'       ? 'selected' : '' }}>Selesai / Disetujui</option>
                    <option value="Perbaikan"       {{ $status == 'Perbaikan'       ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="Ditolak"         {{ $status == 'Ditolak'         ? 'selected' : '' }}>Ditolak</option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit"
                        style="padding:8px 18px;background:#166534;color:#fff;border:none;border-radius:8px;
                               font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap">
                    Cari
                </button>

                {{-- Tombol Reset (hanya muncul jika ada filter aktif) --}}
                @if($search || $status)
                    <a href="{{ route('admin.pengajuan_surat.index') }}"
                       style="padding:8px 14px;background:#f3f4f6;color:#6b7280;border-radius:8px;
                              font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
                        Reset
                    </a>
                @endif

            </form>

            {{-- Info hasil pencarian --}}
            @if($search || $status)
                <div style="margin-top:8px;font-size:.78rem;color:#6b7280">
                    Ditemukan <strong style="color:#166534">{{ $pengajuans->total() }}</strong> hasil
                    @if($search) untuk kata kunci "<strong>{{ $search }}</strong>" @endif
                    @if($status) dengan status "<strong>{{ $status }}</strong>" @endif
                </div>
            @endif
        </div>
        {{-- ===== END FORM PENCARIAN ===== --}}

        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                        <th style="text-align:center;width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $i => $p)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $pengajuans->firstItem() + $i }}</td>
                            <td style="font-size:.78rem;white-space:nowrap">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d F Y, H:i') }}</td>
                            <td>
                                <div style="font-weight:600;color:#111827">{{ $p->user->penduduk ? $p->user->penduduk->nama : $p->user->name }}</div>
                                @if($p->user->penduduk && $p->user->penduduk->nik)
                                    <div style="font-size:.73rem;color:#9ca3af;margin-top:2px;font-family:monospace;letter-spacing:.03em">
                                        NIK: {{ $p->user->penduduk->nik }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.78rem;color:#6b7280;margin-top:2px">{{ $p->jenisSurat->nama_surat }}</div>
                            </td>
                            <td>
                                @if($p->status == 'Menunggu')
                                    <span class="badge-yellow"><span class="badge-dot" style="background:#d97706"></span>Menunggu Proses</span>
                                @elseif($p->status == 'Menunggu Kades')
                                    <span class="badge-blue" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe"><span class="badge-dot" style="background:#2563eb"></span>Menunggu TTD Kades</span>
                                @elseif($p->status == 'Disetujui')
                                    <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Selesai / Disetujui</span>
                                @elseif($p->status == 'Perbaikan')
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;border-radius:99px;font-size:.72rem;font-weight:700">
                                        <span style="width:6px;height:6px;background:#f97316;border-radius:50%"></span>Perlu Perbaikan
                                    </span>
                                @else
                                    <span class="badge-red"><span class="badge-dot" style="background:#dc2626"></span>Ditolak</span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('admin.pengajuan_surat.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:60px 0;color:#9ca3af">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p style="font-weight:600;color:#6b7280">Belum ada pengajuan surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:.78rem;color:#6b7280">{{ $pengajuans->firstItem() }}–{{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} data</span>
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
