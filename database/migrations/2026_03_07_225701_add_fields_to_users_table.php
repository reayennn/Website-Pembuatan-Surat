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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'masyarakat'])->default('masyarakat')->after('password');
            $table->foreignId('penduduk_id')->nullable()->after('role')->constrained('penduduks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['penduduk_id']);
            $table->dropColumn(['role', 'penduduk_id']);
        });
    }
};
