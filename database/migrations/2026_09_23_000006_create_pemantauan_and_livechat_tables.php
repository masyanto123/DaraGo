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
        Schema::create('log_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('tipe_entitas', 50);
        });

        Schema::create('pengumuman_livechat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_perjalanan')->cascadeOnDelete();
            $table->foreignId('armada_id')->nullable()->constrained('armada_bus')->cascadeOnDelete();
            $table->text('pesan_pengumuman');
            $table->timestamp('waktu_dikirim')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman_livechat');
        Schema::dropIfExists('log_lokasi');
    }
};
