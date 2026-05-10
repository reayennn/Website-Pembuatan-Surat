<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class VerifikasiSuratController extends Controller
{
    public function show(string $kode)
    {
        $surat = Surat::with(['pengajuanSurat.user.penduduk', 'pengajuanSurat.jenisSurat'])
            ->where('kode_verifikasi', $kode)
            ->first();

        return view('verifikasi.surat', compact('surat'));
    }
}
