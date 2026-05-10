<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah 2 kolom terakhir yang masih VARCHAR(255) menjadi 100.
     *
     * - pengajuan_surats.file_persyaratan → path file dokumen persyaratan
     * - tanda_tangans.gambar_qr           → path file gambar QR tanda tangan
     *
     * Ukuran 100 dipilih karena nama file yang di-upload sistem
     * menggunakan format: "folder/tahun/bulan/namafile.ext"
     * yang umumnya tidak melebihi 100 karakter.
     */
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->string('file_persyaratan', 100)->nullable()->change();
        });

        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('gambar_qr', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->string('file_persyaratan', 255)->nullable()->change();
        });

        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('gambar_qr', 255)->nullable()->change();
        });
    }
};
