<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class MasyarakatPinController extends Controller
{
    // Maksimal percobaan verifikasi identitas per NIK (anti-abuse reset PIN orang lain)
    const MAX_VERIFY_ATTEMPTS = 3;
    // Lama kunci verifikasi dalam menit
    const VERIFY_LOCK_MINUTES = 60;

    /**
     * Tampilkan form verifikasi identitas (NIK + Tanggal Lahir)
     * untuk memulai proses pembuatan/reset PIN.
     */
    public function showVerifyIdentityForm()
    {
        return view('auth.pin.verify-identity');
    }

    /**
     * Verifikasi identitas masyarakat dengan NIK + Tanggal Lahir.
     * Dilindungi rate limiting per NIK untuk mencegah penyalahgunaan.
     */
    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'nik'           => 'required|string|size:16',
            'tanggal_lahir' => 'required|date',
        ]);

        $nik = $request->nik;
        $cacheKey     = 'pin_reset_attempts_' . $nik;
        $cacheKeyTime = 'pin_reset_locked_until_' . $nik;

        // --- Rate limiting per NIK ---
        $attempts = Cache::get($cacheKey, 0);
        if ($attempts >= self::MAX_VERIFY_ATTEMPTS) {
            return back()->withErrors([
                'nik' => 'Terlalu banyak percobaan verifikasi untuk NIK ini. '
                       . 'Silakan coba lagi dalam ' . self::VERIFY_LOCK_MINUTES . ' menit, '
                       . 'atau hubungi Kantor Desa Karombo untuk bantuan.',
            ])->withInput();
        }

        $penduduk = Penduduk::where('nik', $nik)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if (!$penduduk) {
            // Tambah hitungan gagal untuk NIK ini
            Cache::put($cacheKey, $attempts + 1, now()->addMinutes(self::VERIFY_LOCK_MINUTES));
            $sisaMencoba = self::MAX_VERIFY_ATTEMPTS - ($attempts + 1);

            return back()->withErrors([
                'nik' => 'NIK atau Tanggal Lahir tidak sesuai dengan data kependudukan.'
                       . ($sisaMencoba > 0 ? " Sisa percobaan: {$sisaMencoba} kali." : ' Akun dikunci sementara.'),
            ])->withInput();
        }

        // Berhasil diverifikasi — reset hitungan gagal
        Cache::forget($cacheKey);

        // Simpan penduduk_id di session sebagai token verifikasi sementara
        $request->session()->put('pin_setup_penduduk_id', $penduduk->id);
        $request->session()->put('pin_setup_nama', $penduduk->nama);
        $request->session()->put('pin_sudah_ada', !is_null($penduduk->pin));

        return redirect()->route('pin.create');
    }

    /**
     * Tampilkan form pembuatan/ubah PIN.
     * Hanya bisa diakses jika sudah melewati verifikasi identitas.
     */
    public function showCreatePinForm(Request $request)
    {
        if (!$request->session()->has('pin_setup_penduduk_id')) {
            return redirect()->route('pin.verify-identity')
                ->withErrors(['nik' => 'Silakan verifikasi identitas Anda terlebih dahulu.']);
        }

        $nama       = $request->session()->get('pin_setup_nama');
        $pinSudahAda = $request->session()->get('pin_sudah_ada', false);

        return view('auth.pin.create', compact('nama', 'pinSudahAda'));
    }

    /**
     * Simpan PIN baru untuk penduduk yang sudah terverifikasi.
     * Jika sudah punya PIN sebelumnya, wajib verifikasi PIN lama terlebih dahulu.
     */
    public function savePin(Request $request)
    {
        if (!$request->session()->has('pin_setup_penduduk_id')) {
            return redirect()->route('pin.verify-identity')
                ->withErrors(['nik' => 'Sesi pembuatan PIN telah habis. Silakan verifikasi identitas kembali.']);
        }

        $pendudukId  = $request->session()->get('pin_setup_penduduk_id');
        $pinSudahAda = $request->session()->get('pin_sudah_ada', false);
        $penduduk    = Penduduk::findOrFail($pendudukId);

        // Jika sudah punya PIN — wajib verifikasi PIN lama dulu (seperti sistem bank)
        if ($pinSudahAda) {
            $request->validate([
                'pin_lama'         => 'required|string|digits:6',
                'pin'              => 'required|string|digits:6|confirmed',
                'pin_confirmation' => 'required|string|digits:6',
            ], [
                'pin_lama.required'       => 'PIN lama wajib diisi untuk mengganti PIN.',
                'pin_lama.digits'         => 'PIN lama harus 6 digit angka.',
                'pin.digits'              => 'PIN baru harus terdiri dari 6 digit angka.',
                'pin.confirmed'           => 'Konfirmasi PIN baru tidak cocok.',
                'pin_confirmation.digits' => 'Konfirmasi PIN harus 6 digit angka.',
            ]);

            // Verifikasi PIN lama
            if (!Hash::check($request->pin_lama, $penduduk->pin)) {
                return back()->withErrors([
                    'pin_lama' => 'PIN lama yang Anda masukkan salah. '
                                . 'Jika lupa PIN lama, hubungi Kantor Desa Karombo.',
                ]);
            }

            // Pastikan PIN baru tidak sama dengan PIN lama
            if (Hash::check($request->pin, $penduduk->pin)) {
                return back()->withErrors([
                    'pin' => 'PIN baru tidak boleh sama dengan PIN lama.',
                ]);
            }

        } else {
            // Buat PIN pertama kali — tidak perlu PIN lama
            $request->validate([
                'pin'              => 'required|string|digits:6|confirmed',
                'pin_confirmation' => 'required|string|digits:6',
            ], [
                'pin.digits'              => 'PIN harus terdiri dari 6 digit angka.',
                'pin.confirmed'           => 'Konfirmasi PIN tidak cocok.',
                'pin_confirmation.digits' => 'Konfirmasi PIN harus 6 digit angka.',
            ]);
        }

        // Simpan PIN baru yang sudah di-hash (cast 'hashed' di model menangani hashing)
        $penduduk->update([
            'pin'            => $request->pin,
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);

        // Hapus token sesi verifikasi
        $request->session()->forget(['pin_setup_penduduk_id', 'pin_setup_nama', 'pin_sudah_ada']);

        $pesan = $pinSudahAda
            ? 'PIN berhasil diubah! Silakan login menggunakan NIK dan PIN baru Anda.'
            : 'PIN berhasil dibuat! Silakan login menggunakan NIK dan PIN Anda.';

        return redirect()->route('login.masyarakat')->with('success', $pesan);
    }
}
