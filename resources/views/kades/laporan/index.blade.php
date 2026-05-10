<x-app-layout>
    <x-slot name="header">Laporan Surat</x-slot>

    {{-- Print-only styles --}}
    <style>
        @media print {
            #sidebar, #topbar, #mob-overlay, .no-print { display: none !important; }
            #main { margin-left: 0 !important; padding-top: 0 !important; }
            .page-body { padding: 0 !important; }
            body { background: #fff !important; font-size: 11pt; }
            .print-header { display: block !important; }
            .g-card, [style*="box-shadow"] { box-shadow: none !important; border: 1px solid #d1d5db !important; }
            .pemohon-list { display: block !important; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }
        .print-header { display: none; }
    </style>

    {{-- ══ PRINT HEADER (hanya muncul saat cetak) ══ --}}
    <div class="print-header" style="text-align:center;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #111">
        <div style="font-size:13pt;font-weight:800;text-transform:uppercase;letter-spacing:.05em">PEMERINTAH DESA KAROMBO</div>
        <div style="font-size:10pt;margin-top:2px">Laporan Pengajuan Surat — Periode: <strong>{{ $periode->translatedFormat('F Y') }}</strong></div>
        <div style="font-size:9pt;color:#555;margin-top:2px">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB · Oleh: Kepala Desa</div>
    </div>

    {{-- Header Banner --}}
    <div class="no-print" style="background:linear-gradient(135deg,#0f4c2a 0%,#15803d 60%,#16a34a 100%);border-radius:16px;overflow:hidden;margin-bottom:24px;position:relative;padding:28px 32px;">
        <div style="position:absolute;inset:0;opacity:.06;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='25' stroke='white' stroke-width='1' fill='none'/%3E%3C/svg%3E\");background-size:60px"></div>
        <div style="position:relative;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div>
                <p style="color:rgba(255,255,255,.65);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Sistem Layanan Surat · Desa Karombo</p>
                <h1 style="color:#fff;font-size:1.5rem;font-weight:800;letter-spacing:-.02em;margin-bottom:6px">Laporan Pengajuan Surat — Kepala Desa</h1>
                <p style="color:rgba(255,255,255,.75);font-size:.85rem">Rekap surat yang masuk ke meja Kepala Desa beserta nama pemohon per periode.</p>
            </div>
            {{-- Tombol Cetak --}}
            <a id="btn-cetak-kades"
               href="{{ route('kades.laporan.cetak', array_filter(['bulan'=>$bulan,'tahun'=>$tahun,'nama'=>$nama,'status'=>$status,'jenis_surat_id'=>$jenisSuratId])) }}"
               target="_blank"
               style="display:flex;align-items:center;gap:8px;background:#eab308;color:#713f12;border:none;border-radius:10px;padding:10px 20px;font-size:.85rem;font-weight:800;cursor:pointer;white-space:nowrap;box-shadow:0 4px 12px rgba(0,0,0,.2);text-decoration:none">
                <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Laporan
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="g-card no-print" style="border:none;box-shadow:0 1px 4px rgba(0,0,0,.07);margin-bottom:20px;padding:20px 24px">
        <form method="GET" action="{{ route('kades.laporan.index') }}" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap">
            {{-- Bulan --}}
            <div style="display:flex;flex-direction:column;gap:4px;min-width:150px">
                <label style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Bulan</label>
                <select name="bulan" id="filter-bulan-kades"
                    style="border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#111827;background:#fff;outline:none">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Tahun --}}
            <div style="display:flex;flex-direction:column;gap:4px;min-width:110px">
                <label style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Tahun</label>
                <select name="tahun" id="filter-tahun-kades"
                    style="border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#111827;background:#fff;outline:none">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Nama / NIK Pemohon --}}
            <div style="display:flex;flex-direction:column;gap:4px;flex:1;min-width:180px">
                <label style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Nama / NIK Pemohon</label>
                <div style="position:relative">
                    <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="nama" id="filter-nama-kades" value="{{ $nama }}"
                        placeholder="Cari nama atau NIK pemohon..."
                        style="width:100%;border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px 8px 32px;font-size:.85rem;color:#111827;background:#fff;outline:none">
                </div>
            </div>
            {{-- Jenis Surat --}}
            <div style="display:flex;flex-direction:column;gap:4px;min-width:200px">
                <label style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Jenis Surat</label>
                <select name="jenis_surat_id" id="filter-jenis-kades"
                    style="border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#111827;background:#fff;outline:none">
                    <option value="">— Semua Jenis Surat —</option>
                    @foreach($jenisSurats as $js)
                        <option value="{{ $js->id }}" {{ $jenisSuratId == $js->id ? 'selected' : '' }}>{{ $js->nama_surat }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Status --}}
            <div style="display:flex;flex-direction:column;gap:4px;min-width:160px">
                <label style="font-size:.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em">Status</label>
                <select name="status" id="filter-status-kades"
                    style="border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:.85rem;color:#111827;background:#fff;outline:none">
                    <option value="">— Semua Status —</option>
                    <option value="Menunggu Kades" {{ $status == 'Menunggu Kades' ? 'selected' : '' }}>Menunggu TTD</option>
                    <option value="Disetujui"      {{ $status == 'Disetujui'      ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak"        {{ $status == 'Ditolak'        ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            {{-- Tombol --}}
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" id="btn-filter-laporan-kades"
                    style="background:#15803d;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Tampilkan
                </button>
                @if($nama || $status || $jenisSuratId)
                <a href="{{ route('kades.laporan.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                    style="background:#f3f4f6;color:#374151;border:1.5px solid #e5e7eb;border-radius:8px;padding:9px 14px;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;text-decoration:none">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
                @endif
            </div>
        </form>
        {{-- Badge filter aktif --}}
        @if($nama || $status || $jenisSuratId)
        <div style="margin-top:10px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <span style="font-size:.72rem;color:#6b7280;font-weight:600">Filter aktif:</span>
            @if($jenisSuratId)
            <span style="display:inline-flex;align-items:center;gap:5px;background:#f3e8ff;color:#6b21a8;border-radius:99px;padding:2px 10px;font-size:.75rem;font-weight:700">
                Jenis: {{ $jenisSurats->firstWhere('id', $jenisSuratId)?->nama_surat ?? '-' }}
            </span>
            @endif
            @if($nama)
            <span style="display:inline-flex;align-items:center;gap:5px;background:#dbeafe;color:#1d4ed8;border-radius:99px;padding:2px 10px;font-size:.75rem;font-weight:700">
                Nama: "{{ $nama }}"
            </span>
            @endif
            @if($status)
            <span style="display:inline-flex;align-items:center;gap:5px;background:#fef3c7;color:#92400e;border-radius:99px;padding:2px 10px;font-size:.75rem;font-weight:700">
                Status: {{ $status }}
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
        @php
        $cards = [
            ['label'=>'Total Masuk ke Kades', 'value'=>$summary->total     ?? 0, 'color'=>'#7c3aed','bg'=>'#f5f3ff'],
            ['label'=>'Disetujui',              'value'=>$summary->disetujui ?? 0, 'color'=>'#16a34a','bg'=>'#f0fdf4'],
            ['label'=>'Menunggu TTD',           'value'=>$summary->menunggu  ?? 0, 'color'=>'#d97706','bg'=>'#fffbeb'],
            ['label'=>'Ditolak',                'value'=>$summary->ditolak   ?? 0, 'color'=>'#dc2626','bg'=>'#fef2f2'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="g-card" style="padding:20px 22px;border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px">{{ $c['label'] }}</p>
            <p style="font-size:2rem;font-weight:900;color:{{ $c['color'] }};line-height:1">{{ $c['value'] }}</p>
            <p style="font-size:.72rem;color:#9ca3af;margin-top:4px">{{ $periode->translatedFormat('F Y') }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tabel Detail Per Jenis Surat --}}
    <div class="g-card" style="border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6">
            <h3 style="font-weight:800;color:#111827;font-size:.95rem">Rincian Per Jenis Surat</h3>
            <p style="font-size:.75rem;color:#6b7280;margin-top:2px" class="no-print">Periode: {{ $periode->translatedFormat('F Y') }} — klik baris untuk melihat daftar pemohon</p>
        </div>

        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:.83rem">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                        <th style="padding:10px 18px;text-align:left;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;width:40px">No</th>
                        <th style="padding:10px 18px;text-align:left;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Jenis Surat</th>
                        <th style="padding:10px 18px;text-align:left;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Nama Pemohon</th>
                        <th style="padding:10px 18px;text-align:center;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Total</th>
                        <th style="padding:10px 18px;text-align:center;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Disetujui</th>
                        <th style="padding:10px 18px;text-align:center;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Menunggu TTD</th>
                        <th style="padding:10px 18px;text-align:center;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Ditolak</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $noUrut = 1;
                        $dataAktif = $laporanPerJenis
                            ->where('total', '>', 0)
                            ->when($nama || $status, fn($c) => $c->filter(fn($j) => $j->pengajuanSurats->count() > 0))
                            ->sortByDesc('total');
                    @endphp
                    @forelse($dataAktif as $j)
                        @php
                            $rowId = 'k-pemohon-' . $j->id;
                            $pct   = $j->total > 0 ? round(($j->disetujui / $j->total) * 100) : 0;
                        @endphp
                        <tr onclick="togglePemohon('{{ $rowId }}')"
                            style="border-bottom:1px solid #f3f4f6;cursor:pointer;transition:background .12s"
                            onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                            <td style="padding:12px 18px;color:#9ca3af;font-weight:600;vertical-align:top">{{ $noUrut++ }}</td>
                            <td style="padding:12px 18px;vertical-align:top">
                                <div style="font-weight:700;color:#111827">{{ $j->nama_surat }}</div>
                                <div style="font-size:.72rem;color:#9ca3af">{{ $j->kode_surat }}</div>
                            </td>
                            {{-- Kolom Pemohon --}}
                            <td style="padding:12px 18px;vertical-align:top">
                                {{-- Preview singkat (screen) --}}
                                <div class="no-print" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                                    @php $preview = $j->pengajuanSurats->take(2); @endphp
                                    @foreach($preview as $pv)
                                        <span style="font-size:.75rem;background:#f3f4f6;border-radius:6px;padding:2px 8px;color:#374151;font-weight:500;white-space:nowrap">
                                            {{ $pv->user->penduduk->nama ?? $pv->user->name ?? '-' }}
                                        </span>
                                    @endforeach
                                    @if($j->pengajuanSurats->count() > 2)
                                        <span style="font-size:.72rem;color:#9ca3af">+{{ $j->pengajuanSurats->count() - 2 }} lainnya</span>
                                    @endif
                                    <svg id="icon-{{ $rowId }}" width="13" height="13" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="transition:transform .2s;flex-shrink:0;margin-left:4px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>

                                {{-- Detail dropdown (screen) --}}
                                <div id="{{ $rowId }}" class="pemohon-list" style="display:none;margin-top:8px">
                                    <div style="display:flex;flex-direction:column;gap:3px">
                                        @foreach($j->pengajuanSurats as $idx => $p)
                                            @php
                                                $namaPemohon = $p->user->penduduk->nama ?? $p->user->name ?? '-';
                                                $sc = match($p->status) {
                                                    'Disetujui'     => ['bg'=>'#dcfce7','text'=>'#15803d'],
                                                    'Ditolak'       => ['bg'=>'#fee2e2','text'=>'#b91c1c'],
                                                    'Menunggu Kades'=> ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
                                                    default         => ['bg'=>'#fef3c7','text'=>'#b45309'],
                                                };
                                            @endphp
                                            <div style="display:flex;align-items:center;gap:7px;padding:4px 8px;background:#f9fafb;border-radius:7px;border:1px solid #f3f4f6">
                                                <span style="width:18px;height:18px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.58rem;font-weight:800;color:#374151;flex-shrink:0">{{ $idx + 1 }}</span>
                                                <span style="font-size:.79rem;font-weight:600;color:#111827;flex:1">{{ $namaPemohon }}</span>
                                                <span style="font-size:.65rem;color:#9ca3af;white-space:nowrap">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y, H:i') }}</span>
                                                <span style="font-size:.65rem;font-weight:700;padding:1px 7px;border-radius:99px;background:{{ $sc['bg'] }};color:{{ $sc['text'] }};white-space:nowrap">{{ $p->status }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Versi cetak: semua langsung tampil --}}
                                <div class="print-pemohon" style="display:none">
                                    @foreach($j->pengajuanSurats as $idx => $p)
                                        @php $namaPemohon = $p->user->penduduk->nama ?? $p->user->name ?? '-'; @endphp
                                        <div style="font-size:9pt;padding:2px 0;border-bottom:1px dotted #ddd">
                                            {{ $idx + 1 }}. {{ $namaPemohon }}
                                            <span style="color:#555">({{ $p->status }}, {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td style="padding:12px 18px;text-align:center;vertical-align:top">
                                <span style="font-size:1.1rem;font-weight:800;color:#7c3aed">{{ $j->total }}</span>
                            </td>
                            <td style="padding:12px 18px;text-align:center;vertical-align:top">
                                <span style="display:inline-block;padding:2px 10px;border-radius:99px;font-size:.78rem;font-weight:700;background:#dcfce7;color:#15803d">{{ $j->disetujui }}</span>
                            </td>
                            <td style="padding:12px 18px;text-align:center;vertical-align:top">
                                <span style="display:inline-block;padding:2px 10px;border-radius:99px;font-size:.78rem;font-weight:700;background:#dbeafe;color:#1d4ed8">{{ $j->menunggu }}</span>
                            </td>
                            <td style="padding:12px 18px;text-align:center;vertical-align:top">
                                <span style="display:inline-block;padding:2px 10px;border-radius:99px;font-size:.78rem;font-weight:700;background:#fee2e2;color:#b91c1c">{{ $j->ditolak }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:56px 0;color:#9ca3af">
                                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;color:#d1d5db">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @if($nama || $status)
                                    <p style="font-weight:600;font-size:.9rem">Tidak ada data yang cocok dengan filter pencarian.</p>
                                    <p style="font-size:.8rem;margin-top:4px">Coba ubah kata kunci nama/NIK atau status yang dipilih.</p>
                                @else
                                    <p style="font-weight:600;font-size:.9rem">Tidak ada surat yang masuk ke meja Anda pada periode ini.</p>
                                    <p style="font-size:.8rem;margin-top:4px">Coba pilih bulan atau tahun lain.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dataAktif->count() > 0)
        <div style="padding:12px 20px;border-top:1px solid #f3f4f6;font-size:.75rem;color:#9ca3af" class="no-print">
            Menampilkan <strong style="color:#374151">{{ $dataAktif->count() }}</strong> jenis surat
            @if($nama || $status)
                yang cocok dengan filter pencarian
            @else
                pada periode <strong style="color:#374151">{{ $periode->translatedFormat('F Y') }}</strong>
            @endif.
            Klik baris untuk melihat nama pemohon.
        </div>
        <div style="display:none;padding:12px 20px;border-top:1px solid #ccc;font-size:9pt;color:#555;margin-top:10px" class="print-footer">
            Laporan ini dicetak secara otomatis dari Sistem Layanan Surat Digital Desa Karombo.
        </div>
        @endif
    </div>

    {{-- Tanda tangan area (hanya cetak) --}}
    <div style="display:none;margin-top:48px" class="print-ttd">
        <div style="display:flex;justify-content:flex-end;text-align:center">
            <div style="width:230px">
                <div style="font-size:10pt">{{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-size:10pt;font-weight:700;margin-top:4px">Kepala Desa Karombo</div>
                <div style="height:72px;border-bottom:1px solid #333;margin-top:8px"></div>
                <div style="font-size:10pt;margin-top:6px">( _________________________ )</div>
            </div>
        </div>
    </div>

    <script>
    function togglePemohon(id) {
        const el   = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (!el) return;
        const isOpen = el.style.display !== 'none';
        el.style.display = isOpen ? 'none' : 'block';
        if (icon) icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
    </script>

</x-app-layout>
