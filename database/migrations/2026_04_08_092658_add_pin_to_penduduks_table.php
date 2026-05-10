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
        Schema::table('penduduks', function (Blueprint $table) {
            // Kolom PIN yang di-hash, nullable agar data warga lama tidak terpengaruh
            $table->string('pin')->nullable()->after('nik');
            // Kolom untuk tracking percobaan login yang gagal (rate limiting)
            $table->integer('login_attempts')->default(0)->after('pin');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropColumn(['pin', 'login_attempts', 'locked_until']);
        });
    }
};
