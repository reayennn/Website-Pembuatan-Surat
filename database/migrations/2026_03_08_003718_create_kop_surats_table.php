<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kop_surats', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('baris_1')->nullable();
            $table->string('baris_2')->nullable();
            $table->string('baris_3')->nullable();
            $table->string('baris_4')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kop_surats');
    }
};
