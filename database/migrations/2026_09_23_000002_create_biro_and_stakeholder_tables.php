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
        Schema::create('biro_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_biro', 255);
            $table->text('alamat')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->text('logo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('biro_id')->constrained('biro_perjalanan')->cascadeOnDelete();
            $table->string('posisi', 100);
            $table->timestamps();
        });

        Schema::create('peserta_klien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nama_instansi', 255);
            $table->string('kontak_darurat', 50)->nullable();
            $table->text('catatan_medis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_klien');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('biro_perjalanan');
    }
};
