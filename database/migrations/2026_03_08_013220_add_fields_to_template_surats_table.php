<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->string('nama_surat')->nullable()->after('jenis_surat_id');
            $table->string('judul_surat')->nullable()->after('nama_surat');
            $table->text('keterangan')->nullable()->after('judul_surat');
            $table->text('persyaratan')->nullable()->after('isi_surat');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('persyaratan');
        });
    }

    public function down(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->dropColumn(['nama_surat', 'judul_surat', 'keterangan', 'persyaratan', 'status']);
        });
    }
};
