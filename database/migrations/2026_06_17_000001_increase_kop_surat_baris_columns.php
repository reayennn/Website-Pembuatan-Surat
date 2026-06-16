<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbesar kolom baris_1 – baris_4 di kop_surats menjadi 255 karakter.
     * Fix: kolom sebelumnya hanya 50 karakter (dari migration reduce_all_columns_to_50),
     * sehingga nilai default alamat (56 karakter) tidak bisa disimpan.
     */
    public function up(): void
    {
        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 255)->nullable()->change();
            $table->string('baris_2', 255)->nullable()->change();
            $table->string('baris_3', 255)->nullable()->change();
            $table->string('baris_4', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kop_surats', function (Blueprint $table) {
            $table->string('baris_1', 150)->nullable()->change();
            $table->string('baris_2', 150)->nullable()->change();
            $table->string('baris_3', 150)->nullable()->change();
            $table->string('baris_4', 150)->nullable()->change();
        });
    }
};
