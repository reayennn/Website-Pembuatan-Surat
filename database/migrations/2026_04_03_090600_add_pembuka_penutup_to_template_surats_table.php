<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            // Paragraf pembuka (sebelum tabel data masyarakat) — editable via Quill
            $table->text('isi_pembuka')->nullable()->after('isi_surat');
            // Paragraf penutup (setelah tabel data masyarakat) — editable via Quill
            $table->text('isi_penutup')->nullable()->after('isi_pembuka');
        });
    }

    public function down(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->dropColumn(['isi_pembuka', 'isi_penutup']);
        });
    }
};
