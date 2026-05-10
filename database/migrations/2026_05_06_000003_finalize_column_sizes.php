<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom VARCHAR(255) yang tersisa menjadi ukuran yang sesuai.
     *
     * TIDAK diubah (alasan teknis wajib):
     *  - users.password       → bcrypt hash = 60 karakter, jika < 60 login RUSAK
     *  - users.email          → email bisa > 50 karakter
     *  - penduduks.pin        → bcrypt hash = 60 karakter, jika < 60 login RUSAK
     *  - file_persyaratan     → path file sistem
     *  - tanda_tangans.gambar_qr → path file gambar
     *  - Tabel internal Laravel (cache, sessions, jobs, migrations, dll)
     */
    public function up(): void
    {
        // ─── Tabel: template_surats ─────────────────────────────────────────
        Schema::table('template_surats', function (Blueprint $table) {
            $table->string('nama_surat', 50)->nullable()->change();
            $table->string('judul_surat', 50)->nullable()->change();
        });

        // ─── Tabel: surats ──────────────────────────────────────────────────
        // kode_verifikasi berisi UUID = 36 karakter, aman di 50
        Schema::table('surats', function (Blueprint $table) {
            $table->dropUnique('surats_kode_verifikasi_unique');
            $table->string('kode_verifikasi', 50)->nullable()->change();
            $table->unique('kode_verifikasi');
        });
    }

    /**
     * Rollback ke 255.
     */
    public function down(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->string('nama_surat', 255)->nullable()->change();
            $table->string('judul_surat', 255)->nullable()->change();
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->dropUnique('surats_kode_verifikasi_unique');
            $table->string('kode_verifikasi', 255)->nullable()->change();
            $table->unique('kode_verifikasi');
        });
    }
};
