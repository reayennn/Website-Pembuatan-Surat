<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            // Status warga: 'Aktif' (default), 'Pindah', 'Meninggal'
            $table->enum('status_warga', ['Aktif', 'Pindah', 'Meninggal'])
                  ->default('Aktif')
                  ->after('alamat');

            // Detail kepindahan (diisi saat admin tandai pindah)
            $table->string('alamat_tujuan_pindah')->nullable()->after('status_warga');
            $table->date('tanggal_pindah')->nullable()->after('alamat_tujuan_pindah');
            $table->text('keterangan_pindah')->nullable()->after('tanggal_pindah');
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropColumn([
                'status_warga',
                'alamat_tujuan_pindah',
                'tanggal_pindah',
                'keterangan_pindah',
            ]);
        });
    }
};
