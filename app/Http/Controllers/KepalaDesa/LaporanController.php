<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use App\Models\KopSurat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan         = $request->input('bulan', now()->month);
        $tahun         = $request->input('tahun', now()->year);
        $nama          = trim($request->input('nama', ''));
        $status        = $request->input('status', '');
        $jenisSuratId  = $request->input('jenis_surat_id', '');

        $periode     = Carbon::createFromDate($tahun, $bulan, 1);
        $jenisSurats = JenisSurat::orderBy('nama_surat')->get();

        // Rekap per jenis surat + daftar pemohon — hanya surat yang masuk ke kades
        $query = JenisSurat::withCount([
            'pengajuanSurats as total' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)
                  ->whereYear('tanggal_pengajuan', $tahun)
                  ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak']);
            },
            'pengajuanSurats as disetujui' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)
                  ->whereYear('tanggal_pengajuan', $tahun)
                  ->where('status', 'Disetujui');
            },
            'pengajuanSurats as menunggu' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)
                  ->whereYear('tanggal_pengajuan', $tahun)
                  ->where('status', 'Menunggu Kades');
            },
            'pengajuanSurats as ditolak' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)
                  ->whereYear('tanggal_pengajuan', $tahun)
                  ->where('status', 'Ditolak');
            },
        ])->with([
            'pengajuanSurats' => function ($q) use ($bulan, $tahun, $nama, $status) {
                $q->whereMonth('tanggal_pengajuan', $bulan)
                  ->whereYear('tanggal_pengajuan', $tahun)
                  ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
                  ->with('user.penduduk')
                  ->orderBy('tanggal_pengajuan')
                  ->when($status, fn($q) => $q->where('status', $status))
                  ->when($nama, fn($q) => $q->whereHas('user', function ($uq) use ($nama) {
                      $uq->where('name', 'like', "%{$nama}%")
                         ->orWhereHas('penduduk', fn($pq) => $pq->where('nama', 'like', "%{$nama}%")
                                                                  ->orWhere('nik', 'like', "%{$nama}%"));
                  }));
            }
        ]);

        if ($jenisSuratId) {
            $query->where('id', $jenisSuratId);
        }

        $laporanPerJenis = $query->get();

        // Summary (tidak terpengaruh filter nama/status agar tetap akurat)
        $summaryQ = PengajuanSurat::whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
            ->when($jenisSuratId, fn($q) => $q->where('jenis_surat_id', $jenisSuratId))
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Disetujui' THEN 1 ELSE 0 END) as disetujui,
                SUM(CASE WHEN status = 'Menunggu Kades' THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak
            ");
        $summary = $summaryQ->first();

        // Daftar tahun
        $tahunList = PengajuanSurat::selectRaw('YEAR(tanggal_pengajuan) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        if (!in_array(now()->year, $tahunList)) {
            array_unshift($tahunList, now()->year);
        }

        return view('kades.laporan.index', compact(
            'laporanPerJenis',
            'summary',
            'bulan',
            'tahun',
            'tahunList',
            'periode',
            'nama',
            'status',
            'jenisSurats',
            'jenisSuratId'
        ));
    }

    public function cetak(Request $request)
    {
        $bulan        = $request->input('bulan', now()->month);
        $tahun        = $request->input('tahun', now()->year);
        $nama         = trim($request->input('nama', ''));
        $status       = $request->input('status', '');
        $jenisSuratId = $request->input('jenis_surat_id', '');
        $periode      = Carbon::createFromDate($tahun, $bulan, 1);

        $cetakQuery = JenisSurat::withCount([
            'pengajuanSurats as total' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)->whereYear('tanggal_pengajuan', $tahun)
                  ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak']);
            },
            'pengajuanSurats as disetujui' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)->whereYear('tanggal_pengajuan', $tahun)->where('status', 'Disetujui');
            },
            'pengajuanSurats as menunggu' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)->whereYear('tanggal_pengajuan', $tahun)->where('status', 'Menunggu Kades');
            },
            'pengajuanSurats as ditolak' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pengajuan', $bulan)->whereYear('tanggal_pengajuan', $tahun)->where('status', 'Ditolak');
            },
        ])->with(['pengajuanSurats' => function ($q) use ($bulan, $tahun, $nama, $status) {
            $q->whereMonth('tanggal_pengajuan', $bulan)->whereYear('tanggal_pengajuan', $tahun)
              ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
              ->with('user.penduduk')->orderBy('tanggal_pengajuan')
              ->when($status, fn($q) => $q->where('status', $status))
              ->when($nama, fn($q) => $q->whereHas('user', function ($uq) use ($nama) {
                  $uq->where('name', 'like', "%{$nama}%")
                     ->orWhereHas('penduduk', fn($pq) => $pq->where('nama', 'like', "%{$nama}%")
                                                              ->orWhere('nik', 'like', "%{$nama}%"));
              }));
        }]);

        if ($jenisSuratId) {
            $cetakQuery->where('id', $jenisSuratId);
        }

        $laporanPerJenis = $cetakQuery->get();

        $summary = PengajuanSurat::whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->whereIn('status', ['Menunggu Kades', 'Disetujui', 'Ditolak'])
            ->when($jenisSuratId, fn($q) => $q->where('jenis_surat_id', $jenisSuratId))
            ->when($status, fn($q) => $q->where('status', $status))
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN status='Disetujui' THEN 1 ELSE 0 END) as disetujui, SUM(CASE WHEN status='Menunggu Kades' THEN 1 ELSE 0 END) as menunggu, SUM(CASE WHEN status='Ditolak' THEN 1 ELSE 0 END) as ditolak")
            ->first();

        $jenisSuratNama = $jenisSuratId ? JenisSurat::find($jenisSuratId)?->nama_surat : null;
        $kopSurat = KopSurat::getSettings();

        return view('kades.laporan.cetak', compact(
            'laporanPerJenis', 'summary', 'bulan', 'tahun', 'periode',
            'kopSurat', 'nama', 'status', 'jenisSuratId', 'jenisSuratNama'
        ));
    }
}
