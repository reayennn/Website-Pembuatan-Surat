<?php

namespace App\Http\Controllers\KepalaDesa;

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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $pengajuans = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat'])
            ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user.penduduk', function ($q2) use ($search) {
                        $q2->where('nama', 'like', '%' . $search . '%')
                           ->orWhere('nik', 'like', '%' . $search . '%');
                    })->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('jenisSurat', function ($q2) use ($search) {
                        $q2->where('nama_surat', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('updated_at')
            ->paginate(10)
            ->appends($request->query());

        return view('kades.persetujuan.index', compact('pengajuans', 'search', 'status'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat', 'surat'])
            ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
            ->findOrFail($id);

        $zipEntries = [];
        $filePath   = $pengajuan->file_persyaratan;
        if ($filePath && str_ends_with(strtolower($filePath), '.zip')) {
            $zipAbsPath = storage_path('app/public/' . $filePath);
            if (file_exists($zipAbsPath)) {
                $zip = new \ZipArchive();
                if ($zip->open($zipAbsPath) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        $zipEntries[] = ['index' => $i, 'name' => $stat['name'], 'size' => $stat['size']];
                    }
                    $zip->close();
                }
            }
        }

        return view('kades.persetujuan.show', compact('pengajuan', 'zipEntries'));
    }

    public function downloadSyarat($id, int $index)
    {
        $pengajuan  = PengajuanSurat::findOrFail($id);
        $filePath   = $pengajuan->file_persyaratan;

        abort_if(!$filePath, 404);
        $zipAbsPath = storage_path('app/public/' . $filePath);
        abort_if(!file_exists($zipAbsPath), 404);

        $zip = new \ZipArchive();
        abort_if($zip->open($zipAbsPath) !== true, 500);

        $stat     = $zip->statIndex($index);
        abort_if(!$stat, 404);
        $content  = $zip->getFromIndex($index);
        $fileName = $stat['name'];
        $zip->close();

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

    public function setuju(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::with(['user', 'user.penduduk', 'jenisSurat'])->findOrFail($id);
        
        if ($pengajuan->status !== 'Menunggu Kades') {
            return back()->with('error', 'Status pengajuan tidak valid untuk disetujui.');
        }

        // Ambil seting dari database
        $kopSurat = KopSurat::getSettings();
        $tandaTangan = TandaTangan::getSettings();
        
        // Cari template surat sesuai jenis surat
        $templateSurat = TemplateSurat::where('jenis_surat_id', $pengajuan->jenis_surat_id)->first();
        
        // Generate Nomor Surat — format: 001/Pem.DSK/IV/2026
        $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n') - 1];
        $nomorSurat = str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT)
                    . '/Pem.DSK/'
                    . $bulanRomawi
                    . '/' . date('Y');
        $tanggalSurat = Carbon::now();

        // Siapkan isi surat (ganti placeholder jika ada template)
        $isiSurat = null;
        if ($templateSurat) {
            $penduduk = $pengajuan->user->penduduk;

            // Prioritas: data_pemohon dari form pengajuan citizen, fallback ke penduduk DB
            $dp = $pengajuan->data_pemohon ?? [];

            $nama             = $dp['nama']              ?? ($penduduk?->nama             ?? $pengajuan->user->name);
            $nik              = $dp['nik']               ?? ($penduduk?->nik              ?? '-');
            $tempatLahir      = $dp['tempat_lahir']      ?? ($penduduk?->tempat_lahir     ?? '-');
            $tglLahir         = $dp['tanggal_lahir']     ?? ($penduduk?->tanggal_lahir    ?? null);
            $jenisKelamin     = $dp['jenis_kelamin']     ?? ($penduduk?->jenis_kelamin    ?? '-');
            $statusPerkawinan = $dp['status_perkawinan'] ?? ($penduduk?->status_perkawinan ?? 'Belum Kawin');
            $kewarganegaraan  = $dp['kewarganegaraan']   ?? ($penduduk?->kewarganegaraan  ?? 'WNI');
            $agama            = $dp['agama']             ?? ($penduduk?->agama            ?? '-');
            $pekerjaan        = $dp['pekerjaan']         ?? ($penduduk?->pekerjaan        ?? '-');
            $alamat           = $dp['alamat']            ?? ($penduduk?->alamat           ?? '-');
            $tglFormatted     = $tglLahir ? Carbon::parse($tglLahir)->translatedFormat('d F Y') : '-';

            $isiSurat = $templateSurat->isi_surat;
            $replacements = [
                // Indonesian keys
                '{$nama}'              => $nama,
                '{$nik}'               => $nik,
                '{$tempat_lahir}'      => $tempatLahir,
                '{$tanggal_lahir}'     => $tglFormatted,
                '{$jenis_kelamin}'     => $jenisKelamin,
                '{$status_perkawinan}' => $statusPerkawinan,
                '{$kewarganegaraan}'   => $kewarganegaraan,
                '{$gender}'            => $jenisKelamin,
                '{$agama}'             => $agama,
                '{$pekerjaan}'         => $pekerjaan,
                '{$alamat}'            => $alamat,
                '{$keperluan}'         => '',  // Disembunyikan dari PDF
                // English keys (legacy)
                '{$name}'              => $nama,
                '{$place}'             => $tempatLahir,
                '{$birth_date}'        => $tglFormatted,
                '{$religion}'          => $agama,
                '{$job}'               => $pekerjaan,
                '{$address}'           => $alamat,
                '{$purpose}'           => '',  // Disembunyikan dari PDF
                '{$letter_number}'     => $nomorSurat,
                '{$date}'              => $tanggalSurat->translatedFormat('d F Y'),
            ];

            $isiSurat = str_replace(array_keys($replacements), array_values($replacements), $isiSurat);
        }


        // Generate kode verifikasi unik
        $kodeVerifikasi = Str::uuid()->toString();
        $urlVerifikasi  = route('verifikasi.surat', $kodeVerifikasi);

        // Generate QR Code sebagai string SVG (kompatibel dengan DomPDF)
        $qrCodeSvg = QrCode::format('svg')
            ->size(100)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($urlVerifikasi);

        // Encode ke base64 untuk di-embed di HTML/PDF
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        // Generate PDF
        $pdfName = 'surat_' . Str::slug($pengajuan->jenisSurat->kode_surat) . '_' . $pengajuan->id . '_' . time() . '.pdf';
        $pdfPath = 'surat/' . $pdfName;

        $pdf = Pdf::loadView('surat_template.default', [
            'pengajuan'      => $pengajuan,
            'kopSurat'       => $kopSurat,
            'tandaTangan'    => $tandaTangan,
            'isiSurat'       => $isiSurat,
            'nomorSurat'     => $nomorSurat,
            'tanggalSurat'   => $tanggalSurat,
            'qrCodeBase64'   => $qrCodeBase64,
            'urlVerifikasi'  => $urlVerifikasi,
        ])->setPaper('a4');

        \Storage::disk('public')->put($pdfPath, $pdf->output());

        Surat::create([
            'pengajuan_surat_id' => $pengajuan->id,
            'nomor_surat'        => $nomorSurat,
            'tanggal_surat'      => $tanggalSurat,
            'file_pdf'           => $pdfPath,
            'kode_verifikasi'    => $kodeVerifikasi,
        ]);

        $pengajuan->status = 'Disetujui';
        $pengajuan->save();

        return redirect()->route('kades.persetujuan.index')->with('success', 'Surat berhasil disetujui dan ditandatangani.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|min:5',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);
        
        if ($pengajuan->status !== 'Menunggu Kades') {
            return back()->with('error', 'Status pengajuan tidak valid untuk ditolak.');
        }

        $pengajuan->status = 'Ditolak';
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->save();

        return redirect()->route('kades.persetujuan.index')->with('success', 'Pengajuan surat telah ditolak.');
    }
}
