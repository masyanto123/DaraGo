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
        Schema::create('trip_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biro_id')->constrained('biro_perjalanan')->cascadeOnDelete();
            $table->string('nama_kegiatan', 255);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('status', 50)->default('Upcoming');
            $table->timestamps();
        });

        Schema::create('armada_bus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->nullable()->constrained('trip_perjalanan')->nullOnDelete();
            $table->string('nama_po', 150)->nullable();
            $table->string('nomor_bus', 50);
            $table->foreignId('supir_id')->nullable()->constrained('karyawan')->nullOnDelete();
            $table->foreignId('tour_leader_id')->nullable()->constrained('karyawan')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('manifest_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->foreignId('armada_id')->constrained('armada_bus')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta_klien')->cascadeOnDelete();
            $table->string('nomor_kursi', 10)->nullable();
            $table->boolean('status_presensi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifest_peserta');
        Schema::dropIfExists('armada_bus');
        Schema::dropIfExists('trip_perjalanan');
    }
};
