<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->text('isi_surat')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->text('isi_surat')->nullable(false)->change();
        });
    }
};
