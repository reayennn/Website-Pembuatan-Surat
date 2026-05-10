<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl mb-6" style="background: #0f4c2a; min-height: 130px; border-radius: 16px; overflow: hidden;">
        <div class="absolute inset-0 opacity-5" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Crect width='80' height='80' fill='none'/%3E%3Ccircle cx='0' cy='0' r='30' stroke='white' stroke-width='1' fill='none'/%3E%3Ccircle cx='80' cy='80' r='30' stroke='white' stroke-width='1' fill='none'/%3E%3C/svg%3E\");background-size:80px"></div>
        <div class="relative px-8 py-7 flex items-center justify-between">
            <div>
                <div style="color:#86efac;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Sistem Layanan Surat Digital · Desa Karombo</div>
                <h2 style="color:#fff;font-size:1.5rem;font-weight:800;line-height:1.2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p style="color:rgba(255,255,255,.6);font-size:.83rem;margin-top:6px">Kelola semua pengajuan surat masyarakat dari dashboard ini.</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
                <div style="text-align:right">
                    <div style="color:rgba(255,255,255,.5);font-size:.68rem;font-weight:600;text-transform:uppercase">Hari ini</div>
                    <div style="color:#fff;font-size:.85rem;font-weight:700;margin-top:2px">{{ now()->translatedFormat('d F Y') }}</div>
                </div>
                <div style="background:#eab308;border-radius:14px;padding:14px;box-shadow:0 4px 12px rgba(234,179,8,.3)">
                    <svg width="28" height="28" fill="#15803d" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/></svg>
                </div>
            </div>
        </div>
    </div>

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
                Verifikasi Sekarang →
            </a>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
        @php
        $statsCards = [
            ['label'=>'Total Pengguna','value'=>$stats['total_penduduk'],'color'=>'#2563eb','bg'=>'#eff6ff','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['label'=>'Total Pengajuan','value'=>$stats['total_pengajuan'],'color'=>'#7c3aed','bg'=>'#f5f3ff','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
            ['label'=>'Disetujui','value'=>$stats['pengajuan_disetujui'],'color'=>'#16a34a','bg'=>'#f0fdf4','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['label'=>'Menunggu','value'=>$stats['pengajuan_menunggu'],'color'=>'#d97706','bg'=>'#fffbeb','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ];
        @endphp
        @foreach($statsCards as $s)
        <div class="g-card" style="padding:20px;display:flex;align-items:flex-start;gap:14px;border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="width:46px;height:46px;background:{{$s['bg']}};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="{{$s['color']}}" viewBox="0 0 24 24">{!! $s['icon'] !!}</svg>
            </div>
            <div>
                <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">{{ $s['label'] }}</p>
                <p style="font-size:1.8rem;font-weight:900;color:#111827;line-height:1.1;margin-top:4px">{{ $s['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Links --}}
    <div class="g-card" style="border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6">
            <p style="font-size:.68rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em">Menu Cepat</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;padding:8px">
            @php
            $menus = [
                ['href'=>route('admin.pengajuan_surat.index'),'label'=>'Pengajuan Surat','color'=>'#7c3aed','bg'=>'#f5f3ff','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                ['href'=>route('admin.template_surat.index'),'label'=>'Template Surat','color'=>'#15803d','bg'=>'#f0fdf4','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                ['href'=>route('admin.jenis_surat.index'),'label'=>'Jenis Surat','color'=>'#b45309','bg'=>'#fffbeb','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5l4.586 4.586A2 2 0 0117 8.914V19a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>'],
                ['href'=>route('admin.penduduk.index'),'label'=>'Data Warga','color'=>'#0369a1','bg'=>'#f0f9ff','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ];
            @endphp
            @foreach($menus as $m)
            <a href="{{ $m['href'] }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px 10px;border-radius:12px;text-decoration:none;transition:background .15s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                <div style="width:50px;height:50px;background:{{$m['bg']}};border-radius:14px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.08)">
                    <svg width="24" height="24" fill="none" stroke="{{ $m['color'] }}" viewBox="0 0 24 24">{!! $m['icon'] !!}</svg>
                </div>
                <span style="font-size:.75rem;font-weight:700;color:#374151;text-align:center">{{ $m['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</x-app-layout>
