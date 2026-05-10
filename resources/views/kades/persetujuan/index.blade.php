<x-app-layout>
    <x-slot name="header">Pengajuan Surat</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">

        {{-- Card Header --}}
        <div class="g-card-header">
            <div>
                <h3>Daftar Pengajuan Surat</h3>
                <p>Kelola dan tandatangani permohonan surat dari masyarakat</p>
            </div>
        </div>

        {{-- ===== FORM PENCARIAN ===== --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f3f4f6">
            <form method="GET" action="{{ route('kades.persetujuan.index') }}"
                  style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">

                {{-- Input Pencarian --}}
                <div style="position:relative;flex:1;min-width:200px">
                    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari nama / NIK pemohon atau jenis surat..."
                           style="width:100%;padding:8px 12px 8px 34px;border:1px solid #e5e7eb;border-radius:8px;
                                  font-size:.82rem;outline:none;transition:border .2s;box-sizing:border-box"
                           onfocus="this.style.borderColor='#166534'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                {{-- Filter Status --}}
                <select name="status"
                        style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:.82rem;
                               outline:none;background:#fff;cursor:pointer;min-width:175px"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Kades" {{ $status == 'Menunggu Kades' ? 'selected' : '' }}>Butuh TTD Kades</option>
                    <option value="Disetujui"      {{ $status == 'Disetujui'      ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak"        {{ $status == 'Ditolak'        ? 'selected' : '' }}>Ditolak</option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit"
                        style="padding:8px 18px;background:#166534;color:#fff;border:none;border-radius:8px;
                               font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap">
                    Cari
                </button>

                {{-- Tombol Reset --}}
                @if($search || $status)
                    <a href="{{ route('kades.persetujuan.index') }}"
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

        {{-- Tabel --}}
        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Pemohon</th>
                        <th>NIK</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                        <th>Waktu Update</th>
                        <th style="text-align:center;width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $i => $p)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $pengajuans->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:600;color:#111827">
                                    {{ $p->user->penduduk ? $p->user->penduduk->nama : $p->user->name }}
                                </div>
                            </td>
                            <td>
                                @if($p->user->penduduk && $p->user->penduduk->nik)
                                    <span style="font-size:.78rem;font-family:monospace;color:#374151;background:#f3f4f6;padding:2px 8px;border-radius:5px;white-space:nowrap">
                                        {{ $p->user->penduduk->nik }}
                                    </span>
                                @else
                                    <span style="font-size:.78rem;color:#d1d5db">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.82rem;color:#374151">{{ $p->jenisSurat->nama_surat }}</div>
                            </td>
                            <td>
                                @if($p->status == 'Menunggu Kades')
                                    <span class="badge-yellow"><span class="badge-dot" style="background:#d97706"></span>Butuh TTD Kades</span>
                                @elseif($p->status == 'Disetujui')
                                    <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Disetujui</span>
                                @else
                                    <span class="badge-red"><span class="badge-dot" style="background:#dc2626"></span>Ditolak</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:#6b7280;white-space:nowrap">
                                {{ \Carbon\Carbon::parse($p->updated_at)->translatedFormat('d F Y, H:i') }}
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('kades.persetujuan.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:60px 0;color:#9ca3af">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p style="font-weight:600;color:#6b7280">
                                    @if($search || $status)
                                        Tidak ada hasil yang ditemukan
                                    @else
                                        Belum ada pengajuan surat
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pengajuans->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:.78rem;color:#6b7280">
                    {{ $pengajuans->firstItem() }}–{{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} data
                </span>
                {{ $pengajuans->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
