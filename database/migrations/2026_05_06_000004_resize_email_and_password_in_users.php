<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuaikan ukuran email dan password ke nilai yang lebih logis.
     *
     * - email    → 100  (email praktis selalu < 100 karakter)
     * - password → 100  (hash bcrypt = 60 karakter; 100 memberi ruang lebih)
     *
     * CATATAN TEKNIS:
     * Kolom password TIDAK BOLEH kurang dari 60 karakter karena
     * Laravel menyimpan hash bcrypt yang selalu sepanjang 60 karakter.
     * Jika size < 60, hash terpotong dan semua user tidak bisa login.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->string('email', 100)->change();
            $table->unique('email');
            $table->string('password', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email', 255)->unique()->change();
            $table->string('password', 255)->change();
        });
    }
};
