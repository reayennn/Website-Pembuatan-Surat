<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'keperluan',
        'data_pemohon',
        'file_persyaratan',
        'status',
        'keterangan',
        'tanggal_pengajuan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'data_pemohon'      => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function surat()
    {
        return $this->hasOne(Surat::class);
    }
}
