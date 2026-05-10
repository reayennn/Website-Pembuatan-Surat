<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $fillable = [
        'nik',
        'pin',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'kewarganegaraan',
        'agama',
        'pekerjaan',
        'alamat',
        // Status & Info Pindah
        'status_warga',
        'alamat_tujuan_pindah',
        'tanggal_pindah',
        'keterangan_pindah',
        // Auth
        'login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'pin',
    ];

    protected function casts(): array
    {
        return [
            'locked_until'  => 'datetime',
            'tanggal_pindah'=> 'date',
            'pin'           => 'hashed',
        ];
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    /** Apakah warga ini masih aktif (belum pindah/meninggal)? */
    public function isAktif(): bool
    {
        return ($this->status_warga ?? 'Aktif') === 'Aktif';
    }

    /** Apakah warga ini sudah pindah? */
    public function isPindah(): bool
    {
        return $this->status_warga === 'Pindah';
    }
}
