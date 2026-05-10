<x-app-layout>
    <x-slot name="header">Template Surat</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="g-card" style="box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div class="g-card-header">
            <div>
                <h3>Daftar Template Surat</h3>
                <p>Kelola template isi surat berdasarkan jenis surat</p>
            </div>
            <a href="{{ route('admin.template_surat.create') }}" class="btn btn-yellow">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Template
            </a>
        </div>

        {{-- ===== FORM PENCARIAN ===== --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f3f4f6">
            <form method="GET" action="{{ route('admin.template_surat.index') }}"
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
                           placeholder="Cari nama template atau jenis surat..."
                           style="width:100%;padding:8px 12px 8px 34px;border:1px solid #e5e7eb;border-radius:8px;
                                  font-size:.82rem;outline:none;transition:border .2s;box-sizing:border-box"
                           onfocus="this.style.borderColor='#166534'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                {{-- Filter Status --}}
                <select name="status"
                        style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:.82rem;
                               outline:none;background:#fff;cursor:pointer;min-width:150px"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Aktif"      {{ $status == 'Aktif'      ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit"
                        style="padding:8px 18px;background:#166534;color:#fff;border:none;border-radius:8px;
                               font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap">
                    Cari
                </button>

                {{-- Tombol Reset --}}
                @if($search || $status)
                    <a href="{{ route('admin.template_surat.index') }}"
                       style="padding:8px 14px;background:#f3f4f6;color:#6b7280;border-radius:8px;
                              font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Info hasil pencarian --}}
            @if($search || $status)
                <div style="margin-top:8px;font-size:.78rem;color:#6b7280">
                    Ditemukan <strong style="color:#166534">{{ $templates->total() }}</strong> hasil
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
                        <th>Nama Surat</th>
                        <th>Jenis Surat</th>
                        <th>Keterangan</th>
                        <th style="width:100px">Status</th>
                        <th style="width:100px">Diperbarui</th>
                        <th style="text-align:center;width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $i => $t)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $templates->firstItem() + $i }}</td>
                            <td>
                                <p style="font-weight:600;color:#111827">{{ $t->nama_surat ?? '-' }}</p>
                                @if($t->judul_surat)<p style="font-size:.72rem;color:#9ca3af;margin-top:1px">{{ $t->judul_surat }}</p>@endif
                            </td>
                            <td style="font-size:.82rem;color:#4b5563">{{ $t->jenisSurat->nama_surat ?? '-' }}</td>
                            <td style="font-size:.78rem;color:#6b7280;max-width:200px">{{ Str::limit(strip_tags($t->keterangan ?? '-'), 70) }}</td>
                            <td>
                                @if($t->status === 'Aktif')
                                    <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Aktif</span>
                                @else
                                    <span class="badge-gray"><span class="badge-dot" style="background:#9ca3af"></span>Nonaktif</span>
                                @endif
                            </td>
                            <td style="font-size:.75rem;color:#6b7280">{{ $t->updated_at->format('d M Y') }}</td>
                            <td style="text-align:center">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px">
                                    <a href="{{ route('admin.template_surat.edit', $t->id) }}" class="btn btn-sm btn-primary">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.template_surat.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
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
                            <td colspan="7" style="text-align:center;padding:60px 0">
                                <p style="font-weight:600;color:#6b7280">Belum ada template surat</p>
                                <a href="{{ route('admin.template_surat.create') }}" style="font-size:.82rem;color:#15803d;font-weight:600;margin-top:6px;display:inline-block">+ Buat template pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($templates->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:.76rem;color:#6b7280">Total: {{ $templates->total() }} template</span>
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
