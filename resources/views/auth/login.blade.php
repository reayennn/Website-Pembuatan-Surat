<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Petugas — Desa Karombo</title>

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

        .f-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .f-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .f-check input { accent-color: var(--green-mid); width: 14px; height: 14px; cursor: pointer; }
        .f-check label { font-size: .8rem; color: #64748b; cursor: pointer; }
        .f-forgot { font-size: .78rem; color: var(--green-mid); text-decoration: none; font-weight: 600; }
        .f-forgot:hover { text-decoration: underline; }

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
            <h1>Portal Petugas</h1>
            <p>Masuk menggunakan Email dan Password Anda</p>
        </div>

        <div class="card-body">
            {{-- Session status --}}
            @if (session('status'))
                <div style="background:#f0fdf4;border:1.5px solid #86efac;color:#166534;padding:11px 14px;border-radius:10px;font-size:.8rem;margin-bottom:20px;">
                    {{ session('status') }}
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
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="f-group">
                    <label class="f-label" for="email">Email</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input id="email" class="f-input" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               required autofocus>
                    </div>
                </div>

                <div class="f-group">
                    <label class="f-label" for="password">Password</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="password" class="f-input" type="password" name="password"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                    </div>
                </div>

                <div class="f-row">
                    <div class="f-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="f-forgot">Lupa sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn-main">Masuk ke Sistem</button>
            </form>


        </div>
    </div>

    <p class="footer-note">
        &copy; {{ date('Y') }} Pemerintah Desa Karombo
    </p>

</div>
</body>
</html>
