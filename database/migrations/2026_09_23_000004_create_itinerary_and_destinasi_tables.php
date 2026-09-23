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
        Schema::create('destinasi_wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tempat', 255);
            $table->text('deskripsi')->nullable();
            $table->text('aturan_lokal')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });

        Schema::create('jadwal_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->foreignId('destinasi_id')->nullable()->constrained('destinasi_wisata')->nullOnDelete();
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai');
            $table->string('kegiatan', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_acara');
        Schema::dropIfExists('destinasi_wisata');
    }
};
