<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KopSurat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\TandaTangan;
use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PengajuanSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $pengajuans = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user.penduduk', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%');
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('jenisSurat', function ($q) use ($search) {
                    $q->where('nama_surat', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.pengajuan_surat.index', compact('pengajuans', 'search', 'status'));
    }

    public function show(string $id)
    {
        $pengajuan = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat', 'surat'])->findOrFail($id);

        // Baca daftar isi ZIP jika file adalah .zip
        $zipEntries = [];
        $filePath   = $pengajuan->file_persyaratan;
        if ($filePath && str_ends_with(strtolower($filePath), '.zip')) {
            $zipAbsPath = storage_path('app/public/' . $filePath);
            if (file_exists($zipAbsPath)) {
                $zip = new \ZipArchive();
                if ($zip->open($zipAbsPath) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        $zipEntries[] = [
                            'index' => $i,
                            'name'  => $stat['name'],
                            'size'  => $stat['size'],
                        ];
                    }
                    $zip->close();
                }
            }
        }

        return view('admin.pengajuan_surat.show', compact('pengajuan', 'zipEntries'));
    }

    /**
     * Download satu file individual dari ZIP berkas persyaratan.
     */
    public function downloadSyarat(string $id, int $index)
    {
        $pengajuan  = PengajuanSurat::findOrFail($id);
        $filePath   = $pengajuan->file_persyaratan;

        abort_if(!$filePath, 404, 'File tidak ditemukan.');

        $zipAbsPath = storage_path('app/public/' . $filePath);
        abort_if(!file_exists($zipAbsPath), 404, 'Arsip tidak ditemukan.');

        $zip = new \ZipArchive();
        abort_if($zip->open($zipAbsPath) !== true, 500, 'Gagal membuka arsip.');

        $stat = $zip->statIndex($index);
        abort_if(!$stat, 404, 'File dalam arsip tidak ditemukan.');

        $content  = $zip->getFromIndex($index);
        $fileName = $stat['name'];
        $zip->close();

        // Tentukan MIME type
        $ext  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $mime = match($ext) {
            'pdf'  => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            default => 'application/octet-stream',
        };

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    public function verify(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Kades,Ditolak,Perbaikan',
            'keterangan' => 'nullable|string',
        ]);

        $pengajuan = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat'])->findOrFail($id);
        
        $pengajuan->status = $request->status;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->save();

        if ($request->status === 'Menunggu Kades') {
            return redirect()->route('admin.pengajuan_surat.index')->with('success', 'Pengajuan diteruskan ke Kepala Desa untuk ditandatangani.');
        } elseif ($request->status === 'Perbaikan') {
            return redirect()->route('admin.pengajuan_surat.index')->with('success', 'Status pengajuan dikembalikan ke warga untuk perbaikan.');
        }

        return redirect()->route('admin.pengajuan_surat.index')->with('success', 'Status pengajuan surat berhasil ditolak.');
    }
}
