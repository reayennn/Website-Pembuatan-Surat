<x-app-layout>
    <x-slot name="header">Daftar Jenis Surat</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div class="g-card-header">
            <div>
                <h3>Daftar Jenis Surat</h3>
                <p>Kelola jenis-jenis surat yang dapat diajukan masyarakat</p>
            </div>
            <a href="{{ route('admin.jenis_surat.create') }}" class="btn btn-yellow">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Jenis Surat
            </a>
        </div>

        {{-- ===== FORM PENCARIAN ===== --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f3f4f6">
            <form method="GET" action="{{ route('admin.jenis_surat.index') }}"
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
                           placeholder="Cari kode atau nama jenis surat..."
                           style="width:100%;padding:8px 12px 8px 34px;border:1px solid #e5e7eb;border-radius:8px;
                                  font-size:.82rem;outline:none;transition:border .2s;box-sizing:border-box"
                           onfocus="this.style.borderColor='#166534'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                {{-- Tombol Cari --}}
                <button type="submit"
                        style="padding:8px 18px;background:#166534;color:#fff;border:none;border-radius:8px;
                               font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap">
                    Cari
                </button>

                {{-- Tombol Reset --}}
                @if($search)
                    <a href="{{ route('admin.jenis_surat.index') }}"
                       style="padding:8px 14px;background:#f3f4f6;color:#6b7280;border-radius:8px;
                              font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Info hasil pencarian --}}
            @if($search)
                <div style="margin-top:8px;font-size:.78rem;color:#6b7280">
                    Ditemukan <strong style="color:#166534">{{ $jenisSurats->total() }}</strong> hasil
                    untuk kata kunci "<strong>{{ $search }}</strong>"
                </div>
            @endif
        </div>
        {{-- ===== END FORM PENCARIAN ===== --}}

        <div style="overflow-x:auto">
            <table class="g-table">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th style="width:140px">Kode Surat</th>
                        <th>Nama Surat</th>
                        <th style="text-align:center;width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisSurats as $i => $j)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $jenisSurats->firstItem() + $i }}</td>
                            <td>
                                <span class="badge-green">{{ $j->kode_surat }}</span>
                            </td>
                            <td style="font-weight:500;color:#111827">{{ $j->nama_surat }}</td>
                            <td style="text-align:center">
                                <div style="display:flex;align-items:center;justify-content:center;gap:8px">
                                    <a href="{{ route('admin.jenis_surat.edit', $j->id) }}" class="btn btn-sm btn-primary">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.jenis_surat.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jenis surat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:60px 0;color:#9ca3af">
                                <p style="font-weight:600;color:#6b7280">Belum ada jenis surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jenisSurats->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6">{{ $jenisSurats->links() }}</div>
        @endif
    </div>
</x-app-layout>
