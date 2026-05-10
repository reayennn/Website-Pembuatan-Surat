<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman login khusus petugas — tidak dipublikasikan di landing page
Route::get('/petugas', function () {
    return view('landing-petugas');
})->middleware('guest')->name('landing.petugas');

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Masyarakat\PengajuanController;

Route::middleware(['auth', 'role:masyarakat'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total' => \App\Models\PengajuanSurat::where('user_id', \Illuminate\Support\Facades\Auth::id())->count(),
            'disetujui' => \App\Models\PengajuanSurat::where('user_id', \Illuminate\Support\Facades\Auth::id())->where('status', 'Disetujui')->count(),
            'menunggu' => \App\Models\PengajuanSurat::where('user_id', \Illuminate\Support\Facades\Auth::id())->where('status', 'Menunggu')->count(),
        ];
        return view('dashboard', compact('stats'));
    })->name('dashboard');

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/template/{jenisSuratId}', [PengajuanController::class, 'getTemplate'])->name('pengajuan.template');
    Route::get('/pengajuan/check/{jenisSuratId}', [PengajuanController::class, 'checkEligibility'])->name('pengajuan.check');
    Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
});

use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\PengajuanSuratController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KopSuratController;
use App\Http\Controllers\Admin\TandaTanganController;
use App\Http\Controllers\Admin\TemplateSuratController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('penduduk', PendudukController::class);
    Route::post('/penduduk/{id}/pindah', [PendudukController::class, 'tandaiPindah'])->name('penduduk.pindah');
    Route::patch('/penduduk/{id}/batalkan-pindah', [PendudukController::class, 'batalkanPindah'])->name('penduduk.batalkan-pindah');
    Route::resource('jenis_surat', JenisSuratController::class);

    // Master Surat
    Route::get('/kop_surat', [KopSuratController::class, 'edit'])->name('kop_surat.edit');
    Route::patch('/kop_surat', [KopSuratController::class, 'update'])->name('kop_surat.update');
    Route::get('/tanda_tangan', [TandaTanganController::class, 'edit'])->name('tanda_tangan.edit');
    Route::patch('/tanda_tangan', [TandaTanganController::class, 'update'])->name('tanda_tangan.update');

    // Template Surat
    Route::get('/template_surat', [TemplateSuratController::class, 'index'])->name('template_surat.index');
    Route::get('/template_surat/create', [TemplateSuratController::class, 'create'])->name('template_surat.create');
    Route::post('/template_surat', [TemplateSuratController::class, 'store'])->name('template_surat.store');
    Route::get('/template_surat/{id}/edit', [TemplateSuratController::class, 'edit'])->name('template_surat.edit');
    Route::patch('/template_surat/{id}', [TemplateSuratController::class, 'update'])->name('template_surat.update');
    Route::delete('/template_surat/{id}', [TemplateSuratController::class, 'destroy'])->name('template_surat.destroy');

    // Pengajuan Surat
    Route::get('/pengajuan_surat', [PengajuanSuratController::class, 'index'])->name('pengajuan_surat.index');
    Route::get('/pengajuan_surat/{id}', [PengajuanSuratController::class, 'show'])->name('pengajuan_surat.show');
    Route::put('/pengajuan_surat/{id}/verify', [PengajuanSuratController::class, 'verify'])->name('pengajuan_surat.verify');
    Route::get('/pengajuan_surat/{id}/syarat/{index}', [PengajuanSuratController::class, 'downloadSyarat'])->name('pengajuan_surat.syarat.download');

    // Kelola Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{id}/link', [UserController::class, 'linkPenduduk'])->name('users.link');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Laporan
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [AdminLaporanController::class, 'cetak'])->name('laporan.cetak');
});

use App\Http\Controllers\KepalaDesa\DashboardController as KadesDashboardController;
use App\Http\Controllers\KepalaDesa\PersetujuanController as KadesPersetujuanController;
use App\Http\Controllers\KepalaDesa\LaporanController as KadesLaporanController;

Route::middleware(['auth', 'role:kepala_desa'])->prefix('kepala-desa')->name('kades.')->group(function () {
    Route::get('/dashboard', [KadesDashboardController::class, 'index'])->name('dashboard');
    Route::get('/persetujuan', [KadesPersetujuanController::class, 'index'])->name('persetujuan.index');
    Route::get('/persetujuan/{id}', [KadesPersetujuanController::class, 'show'])->name('persetujuan.show');
    Route::patch('/persetujuan/{id}/setuju', [KadesPersetujuanController::class, 'setuju'])->name('persetujuan.setuju');
    Route::patch('/persetujuan/{id}/tolak', [KadesPersetujuanController::class, 'tolak'])->name('persetujuan.tolak');
    Route::get('/persetujuan/{id}/syarat/{index}', [KadesPersetujuanController::class, 'downloadSyarat'])->name('persetujuan.syarat.download');

    // Laporan
    Route::get('/laporan', [KadesLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [KadesLaporanController::class, 'cetak'])->name('laporan.cetak');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Auth\MasyarakatLoginController;
use App\Http\Controllers\Auth\MasyarakatPinController;

// Login masyarakat (NIK + PIN)
Route::get('/login/masyarakat', [MasyarakatLoginController::class, 'showLoginForm'])->name('login.masyarakat');
Route::post('/login/masyarakat', [MasyarakatLoginController::class, 'login']);

// Alur Buat / Reset PIN masyarakat (verifikasi identitas dengan NIK + Tanggal Lahir)
Route::get('/login/masyarakat/buat-pin', [MasyarakatPinController::class, 'showVerifyIdentityForm'])->name('pin.verify-identity');
Route::post('/login/masyarakat/verifikasi-identitas', [MasyarakatPinController::class, 'verifyIdentity'])->name('pin.verify-identity.post');
Route::get('/login/masyarakat/buat-pin/form', [MasyarakatPinController::class, 'showCreatePinForm'])->name('pin.create');
Route::post('/login/masyarakat/simpan-pin', [MasyarakatPinController::class, 'savePin'])->name('pin.save');

// Verifikasi keaslian surat via QR Code (publik, tanpa login)
Route::get('/verifikasi/{kode}', [VerifikasiSuratController::class, 'show'])->name('verifikasi.surat');

require __DIR__.'/auth.php';

