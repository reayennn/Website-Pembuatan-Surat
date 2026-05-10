<x-app-layout>
    <x-slot name="header">Data Warga</x-slot>

    @if(session('success'))
        <div class="alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Modal Tandai Pindah --}}
    <div id="modal-pindah" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center">
        <div style="background:#fff;border-radius:18px;padding:28px 32px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.25);animation:slideUpModal .25s ease">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                <div style="width:42px;height:42px;background:#fef2f2;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="20" height="20" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <div>
                    <h3 style="font-size:1rem;font-weight:700;color:#111827">Tandai Pindah</h3>
                    <p id="modal-nama-warga" style="font-size:.8rem;color:#6b7280;margin-top:1px"></p>
                </div>
                <button onclick="tutupModalPindah()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-pindah" method="POST">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px">
                    <div>
                        <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:5px">Alamat Tujuan Pindah <span style="color:#dc2626">*</span></label>
                        <input type="text" name="alamat_tujuan_pindah" class="f-input" placeholder="Contoh: Desa Ngali, Kec. Belo, Kab. Bima" required style="font-size:.82rem">
                    </div>
                    <div>
                        <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:5px">Tanggal Pindah <span style="color:#dc2626">*</span></label>
                        <input type="date" name="tanggal_pindah" class="f-input" required style="font-size:.82rem" max="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:5px">Keterangan Tambahan <span style="color:#9ca3af">(opsional)</span></label>
                        <textarea name="keterangan_pindah" class="f-input" rows="2"
                            placeholder="Contoh: Pindah mengikuti suami/istri, pindah karena pekerjaan, dsb."
                            style="font-size:.82rem;resize:vertical"></textarea>
                    </div>
                    <div style="background:#fef9c3;border:1.5px solid #fde68a;border-radius:10px;padding:10px 14px;font-size:.75rem;color:#92400e">
                        <strong>Perhatian:</strong> Setelah ditandai pindah, warga ini <strong>tidak dapat login</strong>
                        ke portal layanan Desa Karombo. Sesi aktif yang ada akan dicabut secara otomatis.
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button type="button" onclick="tutupModalPindah()" class="btn btn-outline" style="flex:1;justify-content:center">Batal</button>
                    <button type="submit" style="flex:1;justify-content:center;background:#dc2626;color:#fff;border:none;border-radius:10px;padding:10px;font-weight:700;font-size:.82rem;cursor:pointer;display:flex;align-items:center;gap:6px">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Konfirmasi Pindah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="g-card">
        <div class="g-card-header">
            <div>
                <h3>Daftar Data Warga</h3>
                <p>Kelola data warga yang dapat mengakses portal layanan surat</p>
            </div>
            <a href="{{ route('admin.penduduk.create') }}" class="btn btn-yellow">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Data Warga
            </a>
        </div>

        {{-- ===== FORM PENCARIAN ===== --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f3f4f6">
            <form method="GET" action="{{ route('admin.penduduk.index') }}"
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
                           placeholder="Cari NIK, nama, pekerjaan, atau alamat..."
                           style="width:100%;padding:8px 12px 8px 34px;border:1px solid #e5e7eb;border-radius:8px;
                                  font-size:.82rem;outline:none;transition:border .2s;box-sizing:border-box"
                           onfocus="this.style.borderColor='#166534'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                {{-- Filter Status Warga --}}
                <select name="status"
                        style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;font-size:.82rem;
                               outline:none;background:#fff;cursor:pointer;min-width:150px"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Aktif"  {{ $status == 'Aktif'  ? 'selected' : '' }}>Aktif</option>
                    <option value="Pindah" {{ $status == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit"
                        style="padding:8px 18px;background:#166534;color:#fff;border:none;border-radius:8px;
                               font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap">
                    Cari
                </button>

                {{-- Tombol Reset --}}
                @if($search || $status)
                    <a href="{{ route('admin.penduduk.index') }}"
                       style="padding:8px 14px;background:#f3f4f6;color:#6b7280;border-radius:8px;
                              font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Info hasil pencarian --}}
            @if($search || $status)
                <div style="margin-top:8px;font-size:.78rem;color:#6b7280">
                    Ditemukan <strong style="color:#166534">{{ $penduduks->total() }}</strong> hasil
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
                        <th style="width:44px">No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Tempat / Tgl. Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Status Warga</th>
                        <th>Pekerjaan</th>
                        <th style="text-align:center;width:200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penduduks as $index => $penduduk)
                        @php $sw = $penduduk->status_warga ?? 'Aktif'; @endphp
                        <tr style="{{ $sw !== 'Aktif' ? 'opacity:.65;background:#fafafa' : '' }}">
                            <td style="color:#9ca3af;font-weight:600">{{ $penduduks->firstItem() + $index }}</td>
                            <td>
                                <span style="font-family:monospace;font-weight:600;color:#111827;font-size:.82rem;letter-spacing:.03em">{{ $penduduk->nik }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600;color:#111827">{{ $penduduk->nama }}</div>
                                <div style="font-size:.75rem;color:#6b7280;margin-top:1px">{{ $penduduk->agama }}</div>
                            </td>
                            <td style="font-size:.82rem">
                                {{ $penduduk->tempat_lahir }},
                                {{ \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') }}
                            </td>
                            <td>
                                @if($penduduk->jenis_kelamin === 'Laki-laki')
                                    <span class="badge-green"><span class="badge-dot" style="background:#16a34a"></span>Laki-laki</span>
                                @else
                                    <span class="badge-yellow"><span class="badge-dot" style="background:#d97706"></span>Perempuan</span>
                                @endif
                            </td>
                            <td>
                                @if($sw === 'Aktif')
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#dcfce7;color:#15803d;border-radius:99px;font-size:.72rem;font-weight:700">
                                        <span style="width:6px;height:6px;background:#22c55e;border-radius:50%"></span>Aktif
                                    </span>
                                @elseif($sw === 'Pindah')
                                    <div>
                                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#fef3c7;color:#b45309;border-radius:99px;font-size:.72rem;font-weight:700">
                                            <span style="width:6px;height:6px;background:#f59e0b;border-radius:50%"></span>Pindah
                                        </span>
                                        @if($penduduk->alamat_tujuan_pindah)
                                            <div style="font-size:.68rem;color:#9ca3af;margin-top:2px;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $penduduk->alamat_tujuan_pindah }}">
                                                → {{ $penduduk->alamat_tujuan_pindah }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#f1f5f9;color:#64748b;border-radius:99px;font-size:.72rem;font-weight:700">
                                        <span style="width:6px;height:6px;background:#94a3b8;border-radius:50%"></span>{{ $sw }}
                                    </span>
                                @endif
                            </td>
                            <td style="color:#374151;font-size:.82rem">{{ $penduduk->pekerjaan ?: '—' }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;justify-content:center">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.penduduk.edit', $penduduk->id) }}"
                                        title="Edit"
                                        style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#f3f4f6;color:#374151;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.75rem;font-weight:600;text-decoration:none;white-space:nowrap;transition:background .15s"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>

                                    @if($sw === 'Aktif')
                                        {{-- Tandai Pindah --}}
                                        <button type="button"
                                            onclick="bukaModalPindah({{ $penduduk->id }}, '{{ addslashes($penduduk->nama) }}')"
                                            title="Tandai Pindah"
                                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#fff7ed;color:#c2410c;border:1.5px solid #fed7aa;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .15s"
                                            onmouseover="this.style.background='#ffedd5'" onmouseout="this.style.background='#fff7ed'">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Pindah
                                        </button>
                                    @else
                                        {{-- Aktifkan kembali --}}
                                        <form method="POST" action="{{ route('admin.penduduk.batalkan-pindah', $penduduk->id) }}"
                                            onsubmit="return confirm('Kembalikan {{ $penduduk->nama }} menjadi Aktif?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                title="Aktifkan Kembali"
                                                style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .15s"
                                                onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('admin.penduduk.destroy', $penduduk->id) }}"
                                        onsubmit="return confirm('Yakin hapus data {{ $penduduk->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            title="Hapus"
                                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#fef2f2;color:#dc2626;border:1.5px solid #fca5a5;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .15s"
                                            onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:60px 0;color:#9ca3af">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;color:#d1d5db"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p style="font-weight:600;color:#6b7280">Belum ada data warga</p>
                                <p style="font-size:.78rem;margin-top:4px">Tambahkan data warga agar mereka dapat mengakses layanan surat</p>
                                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary" style="margin-top:14px;display:inline-flex">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Data Warga Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penduduks->hasPages())
            <div style="padding:14px 18px;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:.78rem;color:#6b7280">{{ $penduduks->firstItem() }}–{{ $penduduks->lastItem() }} dari {{ $penduduks->total() }} data</span>
                {{ $penduduks->links() }}
            </div>
        @endif
    </div>

    <style>
        @keyframes slideUpModal {
            from { opacity: 0; transform: translateY(20px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
    <script>

        // ── Modal pindah ── //
        function bukaModalPindah(id, nama) {
            document.getElementById('form-pindah').action = `/admin/penduduk/${id}/pindah`;
            document.getElementById('modal-nama-warga').textContent = 'Warga: ' + nama;
            const modal = document.getElementById('modal-pindah');
            modal.style.display = 'flex';
        }
        function tutupModalPindah() {
            document.getElementById('modal-pindah').style.display = 'none';
        }
        document.getElementById('modal-pindah').addEventListener('click', function(e) {
            if (e.target === this) tutupModalPindah();
        });
    </script>

</x-app-layout>

