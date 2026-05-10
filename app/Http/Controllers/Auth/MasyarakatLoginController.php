<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MasyarakatLoginController extends Controller
{
    // Maksimal percobaan login sebelum akun dikunci
    const MAX_ATTEMPTS = 5;
    // Lama kunci akun dalam menit
    const LOCK_MINUTES = 15;

    public function showLoginForm()
    {
        return view('auth.login-masyarakat');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'pin' => 'required|string|digits:6',
        ]);

        // Cari penduduk berdasarkan NIK
        $penduduk = Penduduk::where('nik', $request->nik)->first();

        if (!$penduduk) {
            return back()->withErrors([
                'nik' => 'NIK tidak ditemukan dalam data kependudukan.',
            ])->withInput();
        }

        // ── Cek status warga — blokir jika sudah pindah atau meninggal ──
        if (!$penduduk->isAktif()) {
            $statusLabel = $penduduk->status_warga;
            $tgl = $penduduk->tanggal_pindah
                ? \Carbon\Carbon::parse($penduduk->tanggal_pindah)->translatedFormat('d F Y')
                : null;

            if ($penduduk->isPindah()) {
                $pesan  = "NIK ini tercatat telah pindah dari Desa Karombo";
                $pesan .= $tgl ? " pada tanggal {$tgl}" : "";
                $pesan .= $penduduk->alamat_tujuan_pindah
                    ? " ke {$penduduk->alamat_tujuan_pindah}" : "";
                $pesan .= ". Akses layanan hanya untuk warga yang masih berdomisili di Desa Karombo.";
            } else {
                // Meninggal
                $pesan = "Data NIK ini tidak dapat digunakan untuk masuk. Status kependudukan: {$statusLabel}.";
            }

            return back()->withErrors(['nik' => $pesan])->withInput()
                ->with('status_ditolak', $statusLabel)
                ->with('keterangan_pindah', $penduduk->keterangan_pindah);
        }

        // Cek apakah PIN sudah dibuat
        if (!$penduduk->pin) {
            return back()->withErrors([
                'pin' => 'Anda belum memiliki PIN. Silakan buat PIN terlebih dahulu.',
            ])->withInput()->with('no_pin', true);
        }


        // Cek apakah akun sedang dikunci (rate limiting)
        if ($penduduk->locked_until && Carbon::now()->lt($penduduk->locked_until)) {
            $sisaWaktu = Carbon::now()->diffInMinutes($penduduk->locked_until, false);
            $sisaWaktu = max(1, ceil($sisaWaktu));
            return back()->withErrors([
                'pin' => "Akun Anda dikunci sementara karena terlalu banyak percobaan login yang gagal. "
                       . "Coba lagi dalam {$sisaWaktu} menit.",
            ])->withInput();
        }

        // Verifikasi PIN
        if (!Hash::check($request->pin, $penduduk->pin)) {
            // Tambah hitungan percobaan gagal
            $attempts = $penduduk->login_attempts + 1;
            $updateData = ['login_attempts' => $attempts];

            if ($attempts >= self::MAX_ATTEMPTS) {
                $updateData['locked_until'] = Carbon::now()->addMinutes(self::LOCK_MINUTES);
                $updateData['login_attempts'] = 0; // reset counter setelah dikunci
                $penduduk->update($updateData);

                return back()->withErrors([
                    'pin' => 'PIN salah terlalu banyak kali. Akun dikunci selama ' . self::LOCK_MINUTES . ' menit.',
                ])->withInput();
            }

            $penduduk->update($updateData);
            $sisaMencoba = self::MAX_ATTEMPTS - $attempts;

            return back()->withErrors([
                'pin' => "PIN yang Anda masukkan salah. Sisa percobaan: {$sisaMencoba} kali.",
            ])->withInput();
        }

        // PIN benar — reset hitungan percobaan & kunci
        $penduduk->update([
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);

        // Cari atau buat user yang tertaut
        $user = User::where('penduduk_id', $penduduk->id)->first();

        if (!$user) {
            $user = User::create([
                'name'        => $penduduk->nama,
                'email'       => $penduduk->nik . '@desa.com',
                'password'    => Hash::make(Str::random(32)),
                'role'        => 'masyarakat',
                'penduduk_id' => $penduduk->id,
            ]);
        }

        // Pastikan role-nya adalah masyarakat
        if ($user->role !== 'masyarakat') {
            return back()->withErrors([
                'nik' => 'NIK ini terdaftar sebagai petugas/admin. Silakan login melalui halaman login petugas.',
            ])->withInput();
        }

        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
