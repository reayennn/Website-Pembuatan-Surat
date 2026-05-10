<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_penduduk' => \App\Models\Penduduk::count(),
            'total_pengajuan' => \App\Models\PengajuanSurat::count(),
            'pengajuan_disetujui' => \App\Models\PengajuanSurat::where('status', 'Disetujui')->count(),
            'pengajuan_menunggu' => \App\Models\PengajuanSurat::where('status', 'Menunggu')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
