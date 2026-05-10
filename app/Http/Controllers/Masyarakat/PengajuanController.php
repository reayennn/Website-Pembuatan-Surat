<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\KopSurat;
use App\Models\PengajuanSurat;
use App\Models\TandaTangan;
use App\Models\TemplateSurat;
use App\Services\ValidasiSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    // Daftar pengajuan milik user
    public function index()
    {
        $pengajuans = PengajuanSurat::with(['jenisSurat', 'surat'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('masyarakat.pengajuan.index', compact('pengajuans'));
    }

    // Form buat pengajuan
    public function create()
    {
        if (!Auth::user()->penduduk_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Silakan hubungi Admin Desa untuk menautkan akun Anda dengan data NIK sebelum mengajukan surat.');
        }

        $jenisSurats = JenisSurat::all();
        $kop = KopSurat::getSettings();
        $ttd = TandaTangan::getSettings();

        return view('masyarakat.pengajuan.create', compact('jenisSurats', 'kop', 'ttd'));
    }

    // AJAX: Ambil template surat berdasarkan jenis surat
    public function getTemplate($jenisSuratId)
    {
        $template = TemplateSurat::where('jenis_surat_id', $jenisSuratId)
            ->where('status', 'Aktif')
            ->first();

        if (!$template) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'        => true,
            'judul_surat'  => $template->judul_surat,
            'isi_pembuka'  => $template->isi_pembuka,
            'isi_penutup'  => $template->isi_penutup,
            'persyaratan'  => $template->persyaratan,
        ]);
    }

    /**
     * AJAX: Periksa kelayakan pengajuan surat berdasarkan data penduduk.
     * Dipanggil saat masyarakat memilih jenis surat di form.
     */
    public function checkEligibility($jenisSuratId)
    {
        if (!Auth::user()->penduduk_id) {
            return response()->json([
                'valid'    => false,
                'errors'   => ['Akun Anda belum ditautkan dengan data NIK. Hubungi admin desa.'],
                'warnings' => [],
                'info'     => [],
                'status'   => 'Tidak Memenuhi Syarat',
            ]);
        }

        $jenisSurat = JenisSurat::find($jenisSuratId);
        if (!$jenisSurat) {
            return response()->json(['valid' => true, 'errors' => [], 'warnings' => [], 'info' => [], 'status' => 'OK']);
        }

        $penduduk = Auth::user()->penduduk;
        $service  = new ValidasiSuratService();
        $result   = $service->validate($penduduk, $jenisSurat->kode_surat);
        $result['status'] = $service->getStatusLabel($result);
        $result['kode_surat'] = $jenisSurat->kode_surat;

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id'       => 'required|exists:jenis_surats,id',
            'keperluan'            => 'required|string|min:5',
            'file_persyaratan'     => 'required|array|min:1|max:5',
            'file_persyaratan.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB per file
        ], [
            'keperluan.min'              => 'Keperluan minimal harus 5 karakter.',
            'file_persyaratan.required'  => 'Anda wajib melampirkan minimal 1 berkas persyaratan.',
            'file_persyaratan.min'       => 'Anda wajib melampirkan minimal 1 berkas persyaratan.',
            'file_persyaratan.*.mimes'   => 'Format berkas harus PDF, JPG, atau PNG.',
            'file_persyaratan.*.max'     => 'Ukuran setiap berkas maksimal 5 MB.',
        ]);

        if (!Auth::user()->penduduk_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Akun belum ditautkan dengan NIK.');
        }

        // ── SERVER-SIDE VALIDATION (lapisan kedua, mencegah bypass frontend) ──
        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);
        $penduduk   = Auth::user()->penduduk;
        $service    = new ValidasiSuratService();
        $validasi   = $service->validate($penduduk, $jenisSurat->kode_surat);

        if (!$validasi['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('validasi_errors', $validasi['errors'])
                ->with('error', 'Pengajuan ditolak: ' . implode(' ', $validasi['errors']));
        }

        // Simpan data yang diisi citizen di form (untuk surat)
        $dataPemohon = [
            'nama'               => $request->input('nama',               Auth::user()->penduduk?->nama),
            'nik'                => Auth::user()->penduduk?->nik,
            'tempat_lahir'       => $request->input('tempat_lahir',       Auth::user()->penduduk?->tempat_lahir),
            'tanggal_lahir'      => $request->input('tanggal_lahir',      Auth::user()->penduduk?->tanggal_lahir),
            'jenis_kelamin'      => $request->input('jenis_kelamin',      Auth::user()->penduduk?->jenis_kelamin),
            'status_perkawinan'  => $request->input('status_perkawinan',  Auth::user()->penduduk?->status_perkawinan ?? 'Belum Kawin'),
            'kewarganegaraan'    => Auth::user()->penduduk?->kewarganegaraan ?? 'WNI',
            'agama'              => $request->input('agama',              Auth::user()->penduduk?->agama),
            'pekerjaan'          => $request->input('pekerjaan',          Auth::user()->penduduk?->pekerjaan),
            'alamat'             => $request->input('alamat',             Auth::user()->penduduk?->alamat),
        ];

        if ($request->input('jenis_surat_id') == 4) {
            $dataPemohon['kematian_hari'] = $request->input('kematian_hari');
            $dataPemohon['kematian_tanggal'] = $request->input('kematian_tanggal');
            $dataPemohon['kematian_tempat'] = $request->input('kematian_tempat');
            $dataPemohon['kematian_penyebab'] = $request->input('kematian_penyebab');
            $dataPemohon['pelapor_nama'] = $request->input('pelapor_nama');
            $dataPemohon['pelapor_nik'] = $request->input('pelapor_nik');
            $dataPemohon['pelapor_ttl'] = $request->input('pelapor_ttl');
            $dataPemohon['pelapor_pekerjaan'] = $request->input('pelapor_pekerjaan');
            $dataPemohon['pelapor_alamat'] = $request->input('pelapor_alamat');
            $dataPemohon['pelapor_hubungan'] = $request->input('pelapor_hubungan');
        }

        $filePath = null;
        if ($request->hasFile('file_persyaratan')) {
            $files = $request->file('file_persyaratan');

            if (count($files) === 1) {
                // Hanya 1 file — simpan langsung
                $filePath = $files[0]->store('persyaratan', 'public');
            } else {
                // Lebih dari 1 file — gabungkan jadi ZIP
                $zipName  = 'persyaratan/' . uniqid('syarat_', true) . '.zip';
                $zipPath  = storage_path('app/public/' . $zipName);

                // Pastikan direktori ada
                if (!file_exists(dirname($zipPath))) {
                    mkdir(dirname($zipPath), 0775, true);
                }

                $zip = new \ZipArchive();
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    foreach ($files as $file) {
                        $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
                    }
                    $zip->close();
                    $filePath = $zipName;
                } else {
                    // Fallback: simpan file pertama jika ZIP gagal
                    $filePath = $files[0]->store('persyaratan', 'public');
                }
            }
        }

        PengajuanSurat::create([
            'user_id'           => Auth::id(),
            'jenis_surat_id'    => $request->jenis_surat_id,
            'keperluan'         => $request->keperluan,
            'data_pemohon'      => $dataPemohon,
            'file_persyaratan'  => $filePath,
            'status'            => 'Menunggu',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan surat berhasil dikirim dan sedang menunggu verifikasi admin.');
    }

    // Detail pengajuan
    public function show($id)
    {
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'surat', 'user.penduduk'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('masyarakat.pengajuan.show', compact('pengajuan'));
    }

    // Form perbaikan pengajuan
    public function edit($id)
    {
        $pengajuan = PengajuanSurat::where('user_id', Auth::id())
            ->where('status', 'Perbaikan')
            ->findOrFail($id);

        $jenisSurats = JenisSurat::all();
        $kop = KopSurat::getSettings();
        $ttd = TandaTangan::getSettings();

        return view('masyarakat.pengajuan.edit', compact('pengajuan', 'jenisSurats', 'kop', 'ttd'));
    }

    // Update perbaikan pengajuan
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::where('user_id', Auth::id())
            ->where('status', 'Perbaikan')
            ->findOrFail($id);

        $request->validate([
            'jenis_surat_id'       => 'required|exists:jenis_surats,id',
            'keperluan'            => 'required|string|min:5',
            'file_persyaratan'     => 'nullable|array|max:5',
            'file_persyaratan.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'keperluan.min'            => 'Keperluan minimal harus 5 karakter.',
            'file_persyaratan.*.mimes' => 'Format berkas harus PDF, JPG, atau PNG.',
            'file_persyaratan.*.max'   => 'Ukuran setiap berkas maksimal 5 MB.',
        ]);

        if (!Auth::user()->penduduk_id) {
            return redirect()->route('dashboard')->with('error', 'Akun belum ditautkan dengan NIK.');
        }

        // Validasi kelayakan
        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);
        $penduduk   = Auth::user()->penduduk;
        $service    = new ValidasiSuratService();
        $validasi   = $service->validate($penduduk, $jenisSurat->kode_surat);

        if (!$validasi['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('validasi_errors', $validasi['errors'])
                ->with('error', 'Pengajuan ditolak: ' . implode(' ', $validasi['errors']));
        }

        // Ambil data pemohon sebelumnya
        $dataPemohonLama = $pengajuan->data_pemohon ?? [];

        // Timpa dengan data baru dari form
        $dataPemohonBaru = [
            'nama'               => $request->input('nama',               $dataPemohonLama['nama'] ?? Auth::user()->penduduk?->nama),
            'nik'                => $dataPemohonLama['nik'] ?? Auth::user()->penduduk?->nik,
            'tempat_lahir'       => $request->input('tempat_lahir',       $dataPemohonLama['tempat_lahir'] ?? Auth::user()->penduduk?->tempat_lahir),
            'tanggal_lahir'      => $request->input('tanggal_lahir',      $dataPemohonLama['tanggal_lahir'] ?? Auth::user()->penduduk?->tanggal_lahir),
            'jenis_kelamin'      => $request->input('jenis_kelamin',      $dataPemohonLama['jenis_kelamin'] ?? Auth::user()->penduduk?->jenis_kelamin),
            'status_perkawinan'  => $request->input('status_perkawinan',  $dataPemohonLama['status_perkawinan'] ?? Auth::user()->penduduk?->status_perkawinan ?? 'Belum Kawin'),
            'kewarganegaraan'    => $dataPemohonLama['kewarganegaraan'] ?? Auth::user()->penduduk?->kewarganegaraan ?? 'WNI',
            'agama'              => $request->input('agama',              $dataPemohonLama['agama'] ?? Auth::user()->penduduk?->agama),
            'pekerjaan'          => $request->input('pekerjaan',          $dataPemohonLama['pekerjaan'] ?? Auth::user()->penduduk?->pekerjaan),
            'alamat'             => $request->input('alamat',             $dataPemohonLama['alamat'] ?? Auth::user()->penduduk?->alamat),
        ];

        if ($request->input('jenis_surat_id') == 4) {
            $dataPemohonBaru['kematian_hari'] = $request->input('kematian_hari');
            $dataPemohonBaru['kematian_tanggal'] = $request->input('kematian_tanggal');
            $dataPemohonBaru['kematian_tempat'] = $request->input('kematian_tempat');
            $dataPemohonBaru['kematian_penyebab'] = $request->input('kematian_penyebab');
            $dataPemohonBaru['pelapor_nama'] = $request->input('pelapor_nama');
            $dataPemohonBaru['pelapor_nik'] = $request->input('pelapor_nik');
            $dataPemohonBaru['pelapor_ttl'] = $request->input('pelapor_ttl');
            $dataPemohonBaru['pelapor_pekerjaan'] = $request->input('pelapor_pekerjaan');
            $dataPemohonBaru['pelapor_alamat'] = $request->input('pelapor_alamat');
            $dataPemohonBaru['pelapor_hubungan'] = $request->input('pelapor_hubungan');
        }

        $filePath = $pengajuan->file_persyaratan;
        if ($request->hasFile('file_persyaratan')) {
            // Hapus file lama
            if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }

            $files = $request->file('file_persyaratan');

            if (count($files) === 1) {
                $filePath = $files[0]->store('persyaratan', 'public');
            } else {
                $zipName = 'persyaratan/' . uniqid('syarat_', true) . '.zip';
                $zipPath = storage_path('app/public/' . $zipName);

                if (!file_exists(dirname($zipPath))) {
                    mkdir(dirname($zipPath), 0775, true);
                }

                $zip = new \ZipArchive();
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    foreach ($files as $file) {
                        $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
                    }
                    $zip->close();
                    $filePath = $zipName;
                } else {
                    $filePath = $files[0]->store('persyaratan', 'public');
                }
            }
        }

        $pengajuan->update([
            'jenis_surat_id'   => $request->jenis_surat_id,
            'keperluan'        => $request->keperluan,
            'data_pemohon'     => $dataPemohonBaru,
            'file_persyaratan' => $filePath,
            'status'           => 'Menunggu', // Kembalikan ke Menunggu
            'keterangan'       => null, // Reset catatan
            'tanggal_pengajuan'=> now(), // Reset tanggal pengajuan ke saat ini
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success', 'Perbaikan pengajuan berhasil dikirim kembali untuk diverifikasi admin.');
    }
}
