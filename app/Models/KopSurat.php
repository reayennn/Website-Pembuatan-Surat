<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $fillable = ['logo', 'baris_1', 'baris_2', 'baris_3', 'baris_4'];

    // Helper: get the single settings row or create a default one
    public static function getSettings(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'baris_1' => 'PEMERINTAH KABUPATEN BIMA',
            'baris_2' => 'KECAMATAN SAPE',
            'baris_3' => 'KANTOR DESA KAROMBO',
            'baris_4' => 'Jl. Lintas Sape-Wera, Desa Karombo, Kec. Sape, Kab. Bima',
        ]);
    }
}
