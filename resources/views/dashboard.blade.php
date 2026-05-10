<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Gradient Banner --}}
    <div style="box-shadow:0 1px 4px rgba(0,0,0,.08);background:#15803d;border-radius:16px;overflow:hidden;margin-bottom:24px;position:relative">
        <div style="position:absolute;inset:0;opacity:.07;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100'%3E%3Ccircle cx='50' cy='50' r='40' stroke='white' stroke-width='1' fill='none'/%3E%3C/svg%3E\");background-size:100px"></div>
        <div style="padding:28px 32px;position:relative">
            <p style="color:rgba(255,255,255,.65);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Sistem Layanan Surat · Desa Karombo</p>
            <h2 style="color:#fff;font-size:1.6rem;font-weight:800;line-height:1.2;margin-bottom:4px">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p style="color:rgba(255,255,255,.65);font-size:.85rem">Silakan ajukan surat dan pantau status pengajuan Anda di sini.</p>
            <div style="margin-top:8px;color:rgba(255,255,255,.5);font-size:.75rem">
                Dashboard / <span style="color:rgba(255,255,255,.85)">Beranda</span>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px">
        <div class="g-card" style="padding:20px;display:flex;align-items:center;gap:16px;border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="width:46px;height:46px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Total Pengajuan</p>
                <p style="font-size:1.8rem;font-weight:900;color:#111827;line-height:1.1;margin-top:4px">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="g-card" style="padding:20px;display:flex;align-items:center;gap:16px;border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="width:46px;height:46px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#16a34a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Disetujui</p>
                <p style="font-size:1.8rem;font-weight:900;color:#15803d;line-height:1.1;margin-top:4px">{{ $stats['disetujui'] }}</p>
            </div>
        </div>
        <div class="g-card" style="padding:20px;display:flex;align-items:center;gap:16px;border:none;box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div style="width:46px;height:46px;background:#fffbeb;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="22" height="22" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-size:.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Menunggu</p>
                <p style="font-size:1.8rem;font-weight:900;color:#d97706;line-height:1.1;margin-top:4px">{{ $stats['menunggu'] }}</p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert-error" style="margin-bottom:20px">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Info card --}}
    <div class="g-card" style="border:none;box-shadow:0 1px 4px rgba(0,0,0,.07);padding:24px">
        @if(!Auth::user()->penduduk_id)
            <div style="display:flex;gap:14px;align-items:flex-start">
                <div style="width:40px;height:40px;background:#fffbeb;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg width="20" height="20" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p style="font-weight:700;color:#92400e;margin-bottom:4px">Akun Belum Ditautkan</p>
                    <p style="font-size:.85rem;color:#78350f;line-height:1.5">Data profil Anda belum ditautkan dengan NIK. Silakan hubungi <strong>Admin Desa Karombo</strong> untuk menautkan akun dengan data warga Anda agar dapat mulai mengajukan surat.</p>
                </div>
            </div>
        @else
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div>
                    <h3 style="font-size:1rem;font-weight:700;color:#111827;margin-bottom:4px">Informasi Akun</h3>
                    <p style="font-size:.85rem;color:#6b7280">NIK terdaftar: <strong style="color:#111827">{{ Auth::user()->penduduk->nik }}</strong></p>
                    <p style="font-size:.85rem;color:#6b7280">Nama: <strong style="color:#111827">{{ Auth::user()->penduduk->nama }}</strong></p>
                </div>
                <div style="display:flex;gap:10px">
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajukan Surat Baru
                    </a>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline">Lihat Riwayat</a>
                </div>
            </div>
        @endif
    </div>

</x-app-layout>
