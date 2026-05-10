<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = [
        'kode_surat',
        'nama_surat',
    ];

    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }

    public function template()
    {
        return $this->hasOne(TemplateSurat::class, 'jenis_surat_id');
    }
}
