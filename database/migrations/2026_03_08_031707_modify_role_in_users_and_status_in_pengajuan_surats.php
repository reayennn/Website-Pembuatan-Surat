<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter users table role ENUM
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'masyarakat', 'kepala_desa') DEFAULT 'masyarakat'");
        });

        // Alter pengajuan_surats status ENUM
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Kades', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'masyarakat') DEFAULT 'masyarakat'");
        });

        Schema::table('pengajuan_surats', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan_surats MODIFY COLUMN status ENUM('Menunggu', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'");
        });
    }
};
