<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perkecil ukuran kolom string menjadi maksimal 50.
     * Catatan: file_pdf, logo, alamat_tujuan_pindah tidak diperkecil ke 50
     * karena menyimpan path file / alamat yang bisa lebih dari 50 karakter.
     */
    public function up(): void
    {
        // ─── Tabel: penduduks ───────────────────────────────────────────────
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('nama', 50)->change();
            $table->string('tempat_lahir', 50)->change();
            $table->string('agama', 20)->change();
            $table->string('pekerjaan', 50)->change();
            // alamat_tujuan_pindah tetap 100 — alamat bisa > 50 karakter
            $table->string('alamat_tujuan_pindah', 100)->nullable()->change();
        });

        // ─── Tabel: users ───────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });

        // ─── Tabel: jenis_surats ────────────────────────────────────────────
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->string('nama_surat', 50)->change();
        });

        // ─── Tabel: surats ──────────────────────────────────────────────────
        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat', 50)->change();
            // file_pdf tetap 150 — path file sistem bisa > 50 karakter
            // Contoh: "pengajuan_surat/2026/05/abc123-filename.pdf"
        });

        // ─── Tabel: kop_surats ──────────────────────────────────────────────
        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 150)->nullable()->change();
            $table->string('baris_2', 150)->nullable()->change();
            $table->string('baris_3', 150)->nullable()->change();
            $table->string('baris_4', 150)->nullable()->change();
            // logo tetap 150 — path file sistem
        });

        // ─── Tabel: tanda_tangans ───────────────────────────────────────────
        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('nama', 50)->change();
            $table->string('nipd', 30)->nullable()->change();
        });
    }

    /**
     * Rollback ke ukuran sebelumnya.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->string('tempat_lahir', 50)->change();
            $table->string('agama', 20)->change();
            $table->string('pekerjaan', 50)->change();
            $table->string('alamat_tujuan_pindah', 150)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change();
        });

        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->string('nama_surat', 100)->change();
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat', 100)->change();
        });

        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 150)->nullable()->change();
            $table->string('baris_2', 150)->nullable()->change();
            $table->string('baris_3', 150)->nullable()->change();
            $table->string('baris_4', 150)->nullable()->change();
        });

        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('nama', 100)->change();
            $table->string('nipd', 30)->nullable()->change();
        });
    }
};
