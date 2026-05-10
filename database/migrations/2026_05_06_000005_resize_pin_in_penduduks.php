<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuaikan ukuran kolom pin di tabel penduduks.
     *
     * PIN diinput warga sebagai 6 digit angka (misal: 123456),
     * NAMUN disimpan sebagai hash bcrypt yang panjangnya selalu 60 karakter.
     * Contoh nilai tersimpan: $2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9...
     *
     * TIDAK BOLEH kurang dari 60 karakter — hash akan terpotong
     * dan seluruh warga tidak bisa login ke portal layanan surat.
     * Ukuran 100 dipilih sebagai nilai logis sekaligus aman secara teknis.
     */
    public function up(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('pin', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('pin', 255)->nullable()->change();
        });
    }
};
