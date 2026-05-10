<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah ukuran kolom string yang berlebihan menjadi lebih sesuai.
     * Dilakukan tanpa menghapus data (menggunakan ->change()).
     */
    public function up(): void
    {
        // ─── Tabel: penduduks ───────────────────────────────────────────────
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('nama', 100)->change();           // Nama orang, cukup 100
            $table->string('tempat_lahir', 50)->change();    // Nama kota, cukup 50
            $table->string('agama', 20)->change();           // Islam, Kristen, dll.
            $table->string('pekerjaan', 50)->change();       // Petani, PNS, dll.
            $table->string('alamat_tujuan_pindah', 150)->nullable()->change(); // Alamat pindah
        });

        // ─── Tabel: users ───────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change();           // Nama akun user
        });

        // ─── Tabel: jenis_surats ────────────────────────────────────────────
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->string('nama_surat', 100)->change();     // Nama jenis surat
        });

        // ─── Tabel: surats ──────────────────────────────────────────────────
        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat', 100)->change();    // Nomor surat
            $table->string('file_pdf', 150)->nullable()->change(); // Path file PDF
        });

        // ─── Tabel: kop_surats ──────────────────────────────────────────────
        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 100)->nullable()->change(); // Nama instansi
            $table->string('baris_2', 100)->nullable()->change(); // Sub instansi
            $table->string('baris_3', 100)->nullable()->change(); // Sub instansi
            $table->string('baris_4', 100)->nullable()->change(); // Alamat singkat
            $table->string('logo', 150)->nullable()->change();    // Path logo
        });

        // ─── Tabel: tanda_tangans ───────────────────────────────────────────
        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('nama', 100)->change();           // Nama pejabat
            $table->string('nipd', 30)->nullable()->change(); // NIP/NID
        });
    }

    /**
     * Kembalikan ke ukuran default 255 jika rollback.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('nama', 255)->change();
            $table->string('tempat_lahir', 255)->change();
            $table->string('agama', 255)->change();
            $table->string('pekerjaan', 255)->change();
            $table->string('alamat_tujuan_pindah', 255)->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->string('nama_surat', 255)->change();
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat', 255)->change();
            $table->string('file_pdf', 255)->nullable()->change();
        });

        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 255)->nullable()->change();
            $table->string('baris_2', 255)->nullable()->change();
            $table->string('baris_3', 255)->nullable()->change();
            $table->string('baris_4', 255)->nullable()->change();
            $table->string('logo', 255)->nullable()->change();
        });

        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->string('nama', 255)->change();
            $table->string('nipd', 255)->nullable()->change();
        });
    }
};
