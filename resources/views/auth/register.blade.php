<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun — Desa Karombo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green-dark:  #0f4c2a;
            --green-mid:   #166534;
            --green-light: #dcfce7;
            --yellow:      #eab308;
        }
        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: #f1f5f9;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: var(--green-dark);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { color: var(--yellow); }
        .brand-name { font-weight: 800; font-size: .95rem; color: var(--green-dark); line-height: 1.3; }
        .brand-sub  { font-size: .68rem; color: #64748b; }

        .card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
            overflow: hidden;
        }

        .card-head {
            background: var(--green-dark);
            padding: 24px 32px 20px;
        }
        .card-head h1 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .card-head p {
            color: rgba(255,255,255,.55);
            font-size: .78rem;
        }

        /* Role badge inside header */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(234,179,8,.15);
            border: 1px solid rgba(234,179,8,.3);
            color: var(--yellow);
            font-size: .68rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 99px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .role-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--yellow); }

        .card-body { padding: 28px 32px 32px; }

        .error-box {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .8rem;
            color: #991b1b;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .error-box svg { flex-shrink: 0; margin-top: 1px; }

        .f-group { margin-bottom: 16px; }
        .f-label {
            display: block;
            font-size: .7rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 7px;
        }
        .f-wrap { position: relative; }
        .f-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            width: 16px; height: 16px;
        }
        .f-input {
            width: 100%;
            padding: 10px 13px 10px 38px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #fff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .f-input::placeholder { color: #cbd5e1; }
        .f-input:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 3px rgba(22,101,52,.1);
        }

        /* Info note */
        .info-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .78rem;
            color: #166534;
            line-height: 1.5;
        }
        .info-note svg { flex-shrink: 0; margin-top: 1px; }

        .btn-main {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--green-dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: .875rem;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            letter-spacing: .02em;
            transition: background .2s, box-shadow .2s;
            box-shadow: 0 2px 8px rgba(15,76,42,.25);
            margin-top: 8px;
        }
        .btn-main:hover { background: #0a3a20; box-shadow: 0 3px 14px rgba(15,76,42,.35); }
        .btn-main:active { transform: scale(.99); }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: .8rem;
            color: #64748b;
        }
        .login-link a {
            color: var(--green-mid);
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
        }
        .login-link a:hover { text-decoration: underline; }

        .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: .72rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-icon">
            <svg width="22" height="22" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
            </svg>
        </div>
        <div>
            <div class="brand-name">Desa Karombo</div>
            <div class="brand-sub">Layanan Surat Digital</div>
        </div>
    </div>

    {{-- Card --}}
    <div class="card">
        <div class="card-head">
            <h1>Cara Akses Layanan</h1>
            <p>Panduan mengakses sistem layanan surat Desa Karombo</p>
            <div class="role-badge">
                <span class="dot"></span>
                Pendaftaran Masyarakat
            </div>
        </div>

        <div class="card-body">
            {{-- Info note --}}
            <div class="info-note">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>Untuk mengakses layanan, Anda <strong>tidak perlu mendaftar</strong>. Masuk langsung menggunakan <strong>NIK</strong> dan <strong>Tanggal Lahir</strong> Anda yang telah terdaftar di desa.</span>
            </div>

            {{-- Steps --}}
            <div style="margin-bottom:20px;">
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
                    <div style="min-width:28px;height:28px;background:var(--green-dark);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;">1</div>
                    <div>
                        <div style="font-size:.8rem;font-weight:600;color:#1e293b;margin-bottom:2px;">Pastikan NIK Anda Terdaftar</div>
                        <div style="font-size:.75rem;color:#64748b;line-height:1.5;">Data kependudukan Anda harus sudah diinput oleh petugas di Kantor Desa Karombo.</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;">
                    <div style="min-width:28px;height:28px;background:var(--green-dark);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;">2</div>
                    <div>
                        <div style="font-size:.8rem;font-weight:600;color:#1e293b;margin-bottom:2px;">Gunakan NIK &amp; Tanggal Lahir</div>
                        <div style="font-size:.75rem;color:#64748b;line-height:1.5;">Masuk menggunakan Nomor Induk Kependudukan (16 digit) dan Tanggal Lahir Anda.</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="min-width:28px;height:28px;background:var(--green-dark);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;">3</div>
                    <div>
                        <div style="font-size:.8rem;font-weight:600;color:#1e293b;margin-bottom:2px;">Ajukan Surat Online</div>
                        <div style="font-size:.75rem;color:#64748b;line-height:1.5;">Pilih jenis surat, isi formulir, dan tunggu persetujuan dari petugas desa.</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('login.masyarakat') }}" class="btn-main" style="text-decoration:none;text-align:center;display:block;">
                Masuk dengan NIK &amp; Tanggal Lahir
            </a>

            <p class="login-link" style="margin-top:16px;">
                NIK belum terdaftar?
                <a href="tel:+62" style="color:var(--green-mid);">Hubungi Kantor Desa</a>
            </p>
        </div>
    </div>

    <p class="footer-note">
        &copy; {{ date('Y') }} Pemerintah Desa Karombo
    </p>

</div>
</body>
</html>
