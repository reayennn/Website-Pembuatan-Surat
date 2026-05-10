<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $fillable = [
        'jenis_surat_id',
        'nama_surat',
        'judul_surat',
        'keterangan',
        'isi_surat',
        'isi_pembuka',
        'isi_penutup',
        'persyaratan',
        'status',
    ];

    protected $attributes = [
        'isi_surat' => null,
    ];


    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}
