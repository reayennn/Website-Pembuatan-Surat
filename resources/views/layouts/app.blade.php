<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Surat Desa Karombo') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-w: 256px;
            --green-dark: #0f4c2a;
            --green-mid:  #166534;
            --green-light:#dcfce7;
            --yellow:     #eab308;
            --bg:         #f3f4f6;
        }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--bg);
            color: #1e293b;
        }

        /* ── SIDEBAR ── */
        #sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--green-dark);
            display: flex; flex-direction: column;
            z-index: 40;
            transition: transform .25s ease;
        }
        #sidebar .brand {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        #sidebar .brand-icon {
            width: 38px; height: 38px; background: var(--yellow);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        #sidebar .brand-name { color: #fff; font-weight: 800; font-size: .85rem; line-height: 1.3; }
        #sidebar .brand-sub { color: rgba(255,255,255,.55); font-size: .68rem; }

        #sidebar nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        #sidebar nav::-webkit-scrollbar { width: 0; }

        .nav-group-label {
            padding: 10px 20px 4px;
            font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,.35);
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px 9px 20px;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: .82rem; font-weight: 500;
            transition: all .15s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,.08); }
        .nav-item.active {
            color: #fff; background: rgba(255,255,255,.12);
            border-left-color: var(--yellow);
            font-weight: 600;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-item .badge {
            margin-left: auto; background: var(--yellow);
            color: #713f12; font-size: .6rem; font-weight: 800;
            padding: 1px 6px; border-radius: 999px;
        }

        /* Separator */
        .nav-sep { border: none; border-top: 1px solid rgba(255,255,255,.08); margin: 6px 0; }

        /* User info at bottom */
        #sidebar .user-card {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; gap: 10px;
        }
        #sidebar .user-avatar {
            width: 34px; height: 34px; background: var(--yellow);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 800; color: var(--green-dark); flex-shrink: 0;
        }
        #sidebar .user-name { color: #fff; font-size: .78rem; font-weight: 600; }
        #sidebar .user-role { color: rgba(255,255,255,.45); font-size: .68rem; }
        #sidebar .logout-btn {
            margin-left: auto; color: rgba(255,255,255,.45);
            background: none; border: none; cursor: pointer; padding: 4px;
            border-radius: 6px; transition: color .15s, background .15s;
        }
        #sidebar .logout-btn:hover { color: #fca5a5; background: rgba(239,68,68,.15); }

        /* ── TOPBAR ── */
        #topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: 60px; background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex; align-items: center;
            padding: 0 24px;
            z-index: 30;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        #topbar .page-title {
            font-size: .95rem; font-weight: 700; color: #111827;
            display: flex; align-items: center; gap: 8px;
        }
        #topbar .spacer { flex: 1; }
        #topbar .tb-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 8px;
            background: var(--green-light);
            font-size: .72rem; font-weight: 700; color: var(--green-mid);
        }
        #topbar .tb-badge .dot { width: 7px; height: 7px; background: var(--green-mid); border-radius: 50%; }

        /* ── MAIN CONTENT ── */
        #main {
            margin-left: var(--sidebar-w);
            padding-top: 60px;
            min-height: 100vh;
        }
        .page-body { padding: 28px 28px; }

        /* ── Common Card ── */
        .g-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .g-card-header {
            padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between;
            background: var(--green-dark);
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .g-card-header h3 { color: #fff; font-weight: 700; font-size: .88rem; }
        .g-card-header p { color: rgba(255,255,255,.6); font-size: .72rem; margin-top: 2px; }

        /* ── Tables ── */
        .g-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
        .g-table thead tr { background: #f9fafb; border-bottom: 2px solid #e5e7eb; }
        .g-table th { padding: 10px 18px; text-align: left; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; }
        .g-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .12s; }
        .g-table tbody tr:last-child { border-bottom: none; }
        .g-table tbody tr:hover { background: #f9fafb; }
        .g-table td { padding: 12px 18px; color: #374151; }

        /* ── Badges ── */
        .badge-green { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: .7rem; font-weight: 700; border-radius: 999px; }
        .badge-red   { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-size: .7rem; font-weight: 700; border-radius: 999px; }
        .badge-yellow{ display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #fef9c3; color: #92400e; border: 1px solid #fde68a; font-size: .7rem; font-weight: 700; border-radius: 999px; }
        .badge-gray  { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; font-size: .7rem; font-weight: 700; border-radius: 999px; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: .78rem; font-weight: 600; cursor: pointer; border: 1px solid transparent; transition: all .15s; text-decoration: none; }
        .btn-primary { background: var(--green-mid); color: #fff; }
        .btn-primary:hover { background: var(--green-dark); }
        .btn-yellow  { background: var(--yellow); color: #713f12; }
        .btn-yellow:hover { background: #ca8a04; }
        .btn-danger  { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
        .btn-danger:hover { background: #fecaca; }
        .btn-outline { background: #fff; color: #374151; border-color: #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }
        .btn-sm { padding: 5px 10px; font-size: .72rem; border-radius: 6px; }

        /* ── Form fields ── */
        .f-label { display: block; font-size: .72rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
        .f-input { width: 100%; padding: 9px 13px; border: 1.5px solid #d1d5db; border-radius: 9px; font-size: .85rem; color: #111827; font-family: 'Inter', sans-serif; transition: border-color .15s, box-shadow .15s; outline: none; }
        .f-input:focus { border-color: var(--green-mid); box-shadow: 0 0 0 3px rgba(22,101,52,.12); }
        .f-input:read-only, .f-input[readonly] { background: #f9fafb; color: #6b7280; cursor: not-allowed; }
        select.f-input { background-color: #fff; }
        textarea.f-input { resize: vertical; }

        /* ── Alert ── */
        .alert-success { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; font-size: .83rem; font-weight: 500; margin-bottom: 20px; }
        .alert-error   { display: flex; align-items: center; gap: 10px; background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: .83rem; font-weight: 500; margin-bottom: 20px; }

        /* ── Mobile overlay ── */
        #mob-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 35; }
        @media (max-width: 767px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #mob-overlay.show { display: block; }
            #topbar { left: 0; }
            #main { margin-left: 0; }
        }

        /* ── Scrollbar thin ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside id="sidebar">
    {{-- Brand --}}
    <div class="brand">
        <div class="brand-icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="#15803d"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"/></svg>
        </div>
        <div>
            <div class="brand-name">Desa Karombo</div>
            <div class="brand-sub">Layanan Surat Digital</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav>
        @if(Auth::user()->role === 'admin')

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="nav-sep"></div>

            {{-- Data Warga --}}
            <a href="{{ route('admin.penduduk.index') }}"
               class="nav-item {{ request()->routeIs('admin.penduduk.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Data Warga
            </a>

            <div class="nav-sep"></div>

            {{-- Surat --}}
            <div class="nav-group-label">Manajemen Surat</div>

            <a href="{{ route('admin.pengajuan_surat.index') }}"
               class="nav-item {{ request()->routeIs('admin.pengajuan_surat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Pengajuan Surat
                @php
                    $pendingAdmin = \App\Models\PengajuanSurat::where('status', 'Menunggu')->count();
                @endphp
                @if($pendingAdmin > 0)
                    <span class="badge">{{ $pendingAdmin }}</span>
                @endif
            </a>

            <a href="{{ route('admin.template_surat.index') }}"
               class="nav-item {{ request()->routeIs('admin.template_surat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Template Surat
            </a>

            <a href="{{ route('admin.jenis_surat.index') }}"
               class="nav-item {{ request()->routeIs('admin.jenis_surat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5l4.586 4.586A2 2 0 0117 8.914V19a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                Jenis Surat
            </a>

            <div class="nav-sep"></div>

            {{-- Master --}}
            <div class="nav-group-label">Pengaturan</div>

            <a href="{{ route('admin.kop_surat.edit') }}"
               class="nav-item {{ request()->routeIs('admin.kop_surat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Kop Surat
            </a>

            <div class="nav-sep"></div>

            {{-- Laporan --}}
            <div class="nav-group-label">Laporan</div>

            <a href="{{ route('admin.laporan.index') }}"
               class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Surat
            </a>


        @elseif(Auth::user()->role === 'kepala_desa')
            {{-- ═══ KEPALA DESA ═══ --}}

            {{-- Beranda --}}
            <a href="{{ route('kades.dashboard') }}"
               class="nav-item {{ request()->routeIs('kades.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>

            <div class="nav-sep"></div>

            {{-- Pengajuan --}}
            <div class="nav-group-label">Persetujuan Surat</div>

            <a href="{{ route('kades.persetujuan.index') }}"
               class="nav-item {{ request()->routeIs('kades.persetujuan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Pengajuan Surat
                @php
                    $pendingKades = \App\Models\PengajuanSurat::where('status','Menunggu Kades')->count();
                @endphp
                @if($pendingKades > 0)
                    <span class="badge">{{ $pendingKades }}</span>
                @endif
            </a>

            <div class="nav-sep"></div>

            {{-- Laporan Kades --}}
            <div class="nav-group-label">Laporan</div>

            <a href="{{ route('kades.laporan.index') }}"
               class="nav-item {{ request()->routeIs('kades.laporan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Surat
            </a>

        @else
            {{-- MASYARAKAT --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="{{ route('pengajuan.index') }}"
               class="nav-item {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Pengajuan Surat Saya
            </a>
        @endif
    </nav>

    {{-- User Card --}}
    <div class="user-card">
        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div style="overflow:hidden">
            <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ Auth::user()->name }}</div>
            <div class="user-role">
                @if(Auth::user()->role === 'admin')
                    Administrator
                @elseif(Auth::user()->role === 'kepala_desa')
                    Kepala Desa
                @else
                    Masyarakat
                @endif
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" title="Keluar">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- ── TOPBAR ── --}}
<div id="topbar">
    {{-- Mobile hamburger --}}
    <button id="mob-toggle" class="btn btn-outline btn-sm mr-3" style="display:none"
            onclick="document.getElementById('sidebar').classList.toggle('open'); document.getElementById('mob-overlay').classList.toggle('show')">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="page-title">
        @isset($header){{ $header }}@endisset
    </div>
    <div class="spacer"></div>

    {{-- System badge --}}
    <div class="tb-badge" style="margin-left:12px">
        <span class="dot"></span>
        Sistem Aktif
    </div>
</div>

{{-- Mobile overlay --}}
<div id="mob-overlay" onclick="document.getElementById('sidebar').classList.remove('open'); this.classList.remove('show')"></div>

{{-- ── MAIN CONTENT ── --}}
<div id="main">
    <div class="page-body">
        {{ $slot }}
    </div>
</div>

<script>
// Show hamburger on mobile
if (window.innerWidth < 768) {
    document.getElementById('mob-toggle').style.display = 'flex';
}
window.addEventListener('resize', function() {
    const btn = document.getElementById('mob-toggle');
    if (window.innerWidth < 768) {
        btn.style.display = 'flex';
    } else {
        btn.style.display = 'none';
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('mob-overlay').classList.remove('show');
    }
});
</script>

</body>
</html>
