<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;


class DashboardController extends Controller
{
    public function index()
    {
        // Hitung statistik khusus untuk Kepala Desa
        $stats = [
            'perlu_ttd' => PengajuanSurat::where('status', 'Menunggu Kades')->count(),
            'disetujui' => PengajuanSurat::where('status', 'Disetujui')->count(),
            'ditolak'   => PengajuanSurat::where('status', 'Ditolak')->count(),
        ];

        // Ambil 5 pengajuan terbaru yang butuh persetujuan
        $recentPengajuan = PengajuanSurat::with(['user.penduduk', 'jenisSurat'])
            ->where('status', 'Menunggu Kades')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('kades.dashboard', compact('stats', 'recentPengajuan'));
    }
}
