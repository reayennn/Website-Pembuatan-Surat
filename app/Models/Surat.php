<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'pengajuan_surat_id',
        'nomor_surat',
        'tanggal_surat',
        'file_pdf',
        'kode_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class);
    }
}
