<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pinSudahAda ? 'Ubah PIN' : 'Buat PIN' }} — Desa Karombo</title>

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
        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 8px;
            padding: 7px 12px;
        }
        .user-badge svg { color: var(--yellow); width: 14px; height: 14px; flex-shrink: 0; }
        .user-badge span { color: #fff; font-size: .75rem; font-weight: 600; }
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
        .step.done { color: rgba(255,255,255,.8); }
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
        .step.done .step-num {
            background: rgba(255,255,255,.2);
            border-color: rgba(255,255,255,.4);
        }
        .step-divider { width: 24px; height: 1px; background: rgba(255,255,255,.25); }

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
            padding: 10px 38px 10px 38px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: 1.2rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #fff;
            outline: none;
            letter-spacing: 0.3em;
            text-align: center;
            transition: border-color .15s, box-shadow .15s;
        }
        .f-input::placeholder { color: #cbd5e1; letter-spacing: normal; font-size: .875rem; text-align: left; }
        .f-input:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30,64,175,.1);
        }



        /* Strength meter */
        .pin-hint {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: 6px;
            text-align: center;
        }
        .pin-hint.danger { color: #ef4444; }
        .pin-hint.success { color: #166534; }

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
        .btn-main:disabled { background: #94a3b8; box-shadow: none; cursor: not-allowed; }

        .rules-list {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: .75rem;
            color: #64748b;
        }
        .rules-list p { font-weight: 600; margin-bottom: 6px; color: #475569; }
        .rules-list ul { list-style: none; padding: 0; }
        .rules-list ul li {
            padding: 2px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .rules-list ul li::before {
            content: '•';
            color: #1e40af;
            font-weight: 700;
        }

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
            <h1>{{ $pinSudahAda ? 'Ubah PIN' : 'Buat PIN Baru' }}</h1>
            <p>{{ $pinSudahAda ? 'Masukkan PIN lama, lalu buat PIN baru Anda.' : 'Identitas terverifikasi. Silakan buat PIN Anda.' }}</p>
            @if(isset($nama))
            <div class="user-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 10c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286z"/>
                </svg>
                <span>Identitas terverifikasi: {{ $nama }}</span>
            </div>
            @endif
            <div class="step-indicator">
                <div class="step done">
                    <div class="step-num">✓</div>
                    <span>Verifikasi Identitas</span>
                </div>
                <div class="step-divider"></div>
                <div class="step active">
                    <div class="step-num">2</div>
                    <span>Buat PIN</span>
                </div>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="error-box">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="rules-list">
                <p>Ketentuan PIN:</p>
                <ul>
                    <li>Terdiri dari 6 digit angka (0–9)</li>
                    <li>Jangan gunakan tanggal lahir Anda sebagai PIN</li>
                    <li>Jangan gunakan angka berurutan (123456)</li>
                    <li>Simpan PIN Anda dengan aman dan jangan bagikan ke siapapun</li>
                    @if($pinSudahAda)
                    <li><strong>PIN baru tidak boleh sama dengan PIN lama</strong></li>
                    @endif
                </ul>
            </div>

            <form method="POST" action="{{ route('pin.save') }}" id="pinForm">
                @csrf

                {{-- Field PIN Lama — hanya muncul jika sudah punya PIN sebelumnya --}}
                @if($pinSudahAda)
                <div class="f-group" style="border-bottom:1.5px dashed #e2e8f0;padding-bottom:18px;margin-bottom:18px;">
                    <label class="f-label" for="pin_lama">PIN Lama <span style="color:#ef4444">*</span></label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"/>
                        </svg>
                        <input id="pin_lama" class="f-input" type="password" name="pin_lama"
                               placeholder="Masukkan PIN lama Anda" maxlength="6"
                               inputmode="numeric" pattern="[0-9]{6}" required
                               oninput="this.value=this.value.replace(/[^0-9]/g,''); checkMatch();">
                    </div>
                    <p class="pin-hint" style="text-align:left;color:#64748b;margin-top:5px;">
                        Diperlukan untuk verifikasi bahwa Anda pemilik akun ini.
                        Jika lupa PIN lama, hubungi Kantor Desa Karombo.
                    </p>
                </div>
                @endif

                {{-- PIN baru --}}
                <div class="f-group">
                    <label class="f-label" for="pin">{{ $pinSudahAda ? 'PIN Baru' : 'PIN' }}</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <input id="pin" class="f-input" type="password" name="pin"
                               placeholder="6 digit angka" maxlength="6"
                               inputmode="numeric" pattern="[0-9]{6}" required autofocus
                               oninput="onPinInput(this, 'hint1')">
                    </div>
                    <p class="pin-hint" id="hint1">Masukkan 6 digit angka</p>
                </div>

                {{-- Konfirmasi PIN --}}
                <div class="f-group">
                    <label class="f-label" for="pin_confirmation">Konfirmasi PIN</label>
                    <div class="f-wrap">
                        <svg class="f-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <input id="pin_confirmation" class="f-input" type="password" name="pin_confirmation"
                               placeholder="Ulangi 6 digit angka" maxlength="6"
                               inputmode="numeric" pattern="[0-9]{6}" required
                               oninput="onConfirmInput(this, 'hint2')">
                    </div>
                    <p class="pin-hint" id="hint2">Ulangi PIN yang sama</p>
                </div>

                <button type="submit" class="btn-main" id="btnSave" disabled>
                    Simpan PIN
                </button>
            </form>

            <a href="{{ route('pin.verify-identity') }}" class="link-back">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Verifikasi Identitas
            </a>
        </div>
    </div>

    <p class="footer-note">
        &copy; {{ date('Y') }} Pemerintah Desa Karombo
    </p>

</div>

<script>
function updateDots(dotsContainerId, length, prefix, colorClass = 'filled') {
    for (let i = 1; i <= 6; i++) {
        const dot = document.getElementById(prefix + '-' + i);
        dot.className = 'pin-dot';
        if (i <= length) dot.classList.add(colorClass);
    }
}

function isWeakPin(pin) {
    const sequential = ['123456', '234567', '345678', '456789', '567890', '987654', '876543', '765432', '654321', '543210'];
    const repeated = /^(\d)\1{5}$/.test(pin);
    return sequential.includes(pin) || repeated;
}

function onPinInput(input, hintId) {
    input.value = input.value.replace(/[^0-9]/g, '');
    const len = input.value.length;
    const hint = document.getElementById(hintId);

    if (len < 6) {
        hint.textContent = `${len}/6 digit dimasukkan`;
        hint.className = 'pin-hint';
    } else if (isWeakPin(input.value)) {
        hint.textContent = 'PIN terlalu mudah ditebak. Gunakan kombinasi yang berbeda.';
        hint.className = 'pin-hint danger';
    } else {
        hint.textContent = 'PIN terlihat bagus ✓';
        hint.className = 'pin-hint success';
    }
    checkMatch();
}

function onConfirmInput(input, hintId) {
    input.value = input.value.replace(/[^0-9]/g, '');
    const len = input.value.length;
    const pin = document.getElementById('pin').value;
    const hint = document.getElementById(hintId);

    if (len === 6 && input.value === pin) {
        hint.textContent = 'PIN cocok ✓';
        hint.className = 'pin-hint success';
    } else {
        if (len === 6 && input.value !== pin) {
            hint.textContent = 'PIN tidak cocok!';
            hint.className = 'pin-hint danger';
        } else {
            hint.textContent = `${len}/6 digit dimasukkan`;
            hint.className = 'pin-hint';
        }
    }
    checkMatch();
}

function checkMatch() {
    const pin = document.getElementById('pin').value;
    const conf = document.getElementById('pin_confirmation').value;
    const btn = document.getElementById('btnSave');
    const pinLamaEl = document.getElementById('pin_lama');

    // Jika ada field PIN lama (mode ubah PIN), wajib diisi 6 digit
    const pinLamaOk = !pinLamaEl || pinLamaEl.value.length === 6;
    const valid = pin.length === 6 && conf.length === 6 && pin === conf && !isWeakPin(pin) && pinLamaOk;
    btn.disabled = !valid;
}
</script>
</body>
</html>
