<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Identitas — Desa Karombo</title>

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
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
            overflow: hidden;
        }
        .card-head {
            background: #1e40af;
            padding: 24px 32px 20px;
        }
        .card-head h1 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .card-head p {
            color: rgba(255,255,255,.6);
            font-size: .78rem;
        }
        .step-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .68rem;
            color: rgba(255,255,255,.6);
        }
        .step.active { color: #fff; font-weight: 600; }
        .step-num {
            width: 20px; height: 20px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: .65rem;
        }
        .step.active .step-num {
            background: var(--yellow);
            border-color: var(--yellow);
            color: #000;
            font-weight: 700;
        }
        .step-divider { width: 24px; height: 1px; background: rgba(255,255,255,.25); }

        .card-body { padding: 28px 32px 32px; }
        .info-box {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 20px;
            font-size: .8rem;
            color: #1e40af;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .info-box svg { flex-shrink: 0; margin-top: 1px; }
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
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30,64,175,.1);
        }
        .btn-main {
            display: block;
            width: 100%;
            padding: 12px;
            background: #1e40af;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: .875rem;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            letter-spacing: .02em;
            transition: background .2s, box-shadow .2s;
            box-shadow: 0 2px 8px rgba(30,64,175,.25);
        }
        .btn-main:hover { background: #1e3a8a; box-shadow: 0 3px 14px rgba(30,64,175,.35); }
        .btn-main:active { transform: scale(.99); }
        .link-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: .8rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            padding: 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            transition: all .15s;
            margin-top: 12px;
        }
        .link-back:hover {
            border-color: var(--green-mid);
            color: var(--green-mid);
            background: var(--green-light);
        }
        .link-back svg { width: 14px; height: 14px; }
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

    <div class="card">
        <div class="card-head">
            <h1>Buat / Reset PIN</h1>
            <p>Verifikasi identitas untuk melanjutkan pembuatan PIN</p>
            <div class="step-indicator">
                <div class="step active">
                    <div class="step-num">1</div>
                    <span>Verifikasi Identitas</span>
                </div>
                <div class="step-divider"></div>
                <div class="step">
                    <div class="step-num">2</div>
                    <span>Buat PIN</span>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="info-box">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>Masukkan NIK dan Tanggal Lahir Anda sesuai data kependudukan untuk memverifikasi identitas. Setelah terverifikasi, Anda dapat membuat PIN baru.</span>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('pin.verify-identity.post') }}">
                @csrf

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

                <div class="f-group">
                    <label class="f-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input id="tanggal_lahir" class="f-input" type="date" name="tanggal_lahir" required>
                    </div>
                </div>

                <button type="submit" class="btn-main" id="btn-verify">Verifikasi &amp; Lanjutkan</button>
            </form>

            <a href="{{ route('login.masyarakat') }}" class="link-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Login
            </a>
        </div>
    </div>

    <p class="footer-note">
        NIK belum terdaftar? Hubungi Kantor Desa Karombo untuk pendataan.<br>
        &copy; {{ date('Y') }} Pemerintah Desa Karombo
    </p>

</div>

<script>
document.getElementById('nik').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>
