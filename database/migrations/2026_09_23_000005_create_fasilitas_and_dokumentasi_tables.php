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
        Schema::create('fasilitas_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta_klien')->cascadeOnDelete();
            $table->string('kategori', 100);
            $table->string('detail_fasilitas', 255);
            $table->timestamps();
        });

        Schema::create('dokumentasi_trip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->text('url_media');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu_upload')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_trip');
        Schema::dropIfExists('fasilitas_peserta');
    }
};
