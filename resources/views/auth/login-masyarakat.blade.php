<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Masyarakat — Desa Karombo</title>

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

        /* Brand mark on top */
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

        /* Card */
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
            overflow: hidden;
        }

        /* Card header band */
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

        /* Security badge */
        .security-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 6px;
            padding: 5px 10px;
            width: fit-content;
        }
        .security-badge svg { color: var(--yellow); width: 12px; height: 12px; }
        .security-badge span { color: rgba(255,255,255,.75); font-size: .68rem; font-weight: 500; }

        /* Card body */
        .card-body { padding: 28px 32px 32px; }

        /* Success box */
        .success-box {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .8rem;
            color: #166534;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .success-box svg { flex-shrink: 0; margin-top: 1px; }

        /* Error */
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

        /* Warning box */
        .warning-box {
            background: #fffbeb;
            border: 1.5px solid #fcd34d;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .8rem;
            color: #92400e;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .warning-box svg { flex-shrink: 0; margin-top: 2px; }
        .warning-box a { color: #0f4c2a; font-weight: 700; text-decoration: none; }
        .warning-box a:hover { text-decoration: underline; }

        /* Form */
        .f-group { margin-bottom: 18px; }
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



        .f-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }
        .f-check input { accent-color: var(--green-mid); width: 14px; height: 14px; cursor: pointer; }
        .f-check label { font-size: .8rem; color: #64748b; cursor: pointer; }

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
        }
        .btn-main:hover { background: #0a3a20; box-shadow: 0 3px 14px rgba(15,76,42,.35); }
        .btn-main:active { transform: scale(.99); }



        .footer-note {
            margin-top: 24px;
            text-align: center;
            font-size: .72rem;
            color: #94a3b8;
            line-height: 1.6;
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
            <h1>Portal Masyarakat</h1>
            <p>Masuk menggunakan NIK dan PIN 6 digit Anda</p>
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 10c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286z"/>
                </svg>
                <span>Login Aman dengan PIN Terenkripsi</span>
            </div>
        </div>

        <div class="card-body">

            {{-- Success message --}}
            @if (session('success'))
                <div class="success-box">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="error-box">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>

                {{-- Jika belum punya PIN, tampilkan tombol buat PIN --}}
                @if (session('no_pin'))
                    <div class="warning-box">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Anda belum memiliki PIN. <a href="{{ route('pin.verify-identity') }}">Klik di sini untuk membuat PIN</a> menggunakan NIK dan Tanggal Lahir Anda.</span>
                    </div>
                @endif
            @endif

            <form method="POST" action="{{ route('login.masyarakat') }}">
                @csrf

                {{-- NIK --}}
                <div class="f-group">
                    <label class="f-label" for="nik">Nomor Induk Kependudukan (NIK)</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.333 0 4 .667 4 2v1H5v-1c0-1.333 2.667-2 4-2z"/>
                        </svg>
                        <input id="nik" class="f-input" type="text" name="nik"
                               value="{{ old('nik') }}"
                               placeholder="Masukkan 16 digit NIK"
                               maxlength="16" inputmode="numeric" required autofocus>
                    </div>
                </div>

                {{-- PIN --}}
                <div class="f-group">
                    <label class="f-label" for="pin">PIN (6 Digit Angka)</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <input id="pin" class="f-input" type="password" name="pin"
                               placeholder="Masukkan 6 digit PIN"
                               maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                    </div>
                    <p style="font-size:.7rem;color:#94a3b8;margin-top:6px;text-align:right;">
                        Belum punya PIN?
                        <a href="{{ route('pin.verify-identity') }}" style="color:#166534;font-weight:700;text-decoration:none;">Buat / Reset PIN</a>
                    </p>
                </div>

                <div class="f-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-main" id="btn-login">Masuk ke Akun Saya</button>
            </form>


        </div>
    </div>

    <p class="footer-note">
        NIK belum terdaftar? Hubungi Kantor Desa Karombo untuk pendataan.<br>
        &copy; {{ date('Y') }} Pemerintah Desa Karombo
    </p>

</div>

<script>


// Only allow numeric input for NIK and PIN
document.getElementById('nik').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
document.getElementById('pin').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>
