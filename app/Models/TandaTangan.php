<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TandaTangan extends Model
{
    protected $fillable = ['nipd', 'nama', 'gambar_qr'];

    public static function getSettings(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'nipd' => '',
            'nama' => 'Kepala Desa Karombo',
        ]);
    }
}
