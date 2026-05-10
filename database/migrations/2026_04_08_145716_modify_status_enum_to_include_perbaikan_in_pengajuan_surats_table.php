<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Kades', 'Disetujui', 'Ditolak', 'Perbaikan') DEFAULT 'Menunggu'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Kades', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'");
        });
    }
};
