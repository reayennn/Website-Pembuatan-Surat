<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            // Tambah 2 kolom baru setelah kolom jenis_kelamin agar urutan mirip KTP
            $table->string('status_perkawinan', 50)->nullable()->default('Belum Kawin')->after('jenis_kelamin');
            $table->string('kewarganegaraan', 50)->nullable()->default('WNI')->after('status_perkawinan');
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropColumn(['status_perkawinan', 'kewarganegaraan']);
        });
    }
};
