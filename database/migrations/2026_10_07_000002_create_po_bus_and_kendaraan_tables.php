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
        // 1. Master Perusahaan Otobus (PO Bus)
        Schema::create('po_bus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_po', 150);
            $table->text('alamat')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->text('detail')->nullable();
            $table->timestamps();
        });

        // 2. Anakan Data: Armada Kendaraan per PO
        Schema::create('kendaraan_bus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('po_bus')->cascadeOnDelete();
            $table->string('nomor_bus', 50); // Plat/Nomor Bus, misal: BUS 01 (AB 7001 DA)
            $table->string('tipe_bus', 100)->nullable(); // Big Bus SHD, Medium Bus, Sleeper, dll
            $table->integer('kapasitas')->default(50)->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });

        // 3. Anakan Data: Supir per PO
        Schema::create('supir_po', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('po_bus')->cascadeOnDelete();
            $table->string('nama_supir', 150);
            $table->string('kontak', 50)->nullable();
            $table->string('nomor_sim', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Update tabel armada_bus agar terhubung ke po_bus, kendaraan_bus, dan supir_po
        Schema::table('armada_bus', function (Blueprint $table) {
            $table->foreignId('po_id')->nullable()->after('trip_id')->constrained('po_bus')->nullOnDelete();
            $table->foreignId('kendaraan_id')->nullable()->after('po_id')->constrained('kendaraan_bus')->nullOnDelete();
            $table->foreignId('supir_po_id')->nullable()->after('nomor_bus')->constrained('supir_po')->nullOnDelete();
            $table->string('nama_supir', 150)->nullable()->after('supir_po_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('armada_bus', function (Blueprint $table) {
            $table->dropForeign(['po_id']);
            $table->dropForeign(['kendaraan_id']);
            $table->dropForeign(['supir_po_id']);
            $table->dropColumn(['po_id', 'kendaraan_id', 'supir_po_id', 'nama_supir']);
        });

        Schema::dropIfExists('supir_po');
        Schema::dropIfExists('kendaraan_bus');
        Schema::dropIfExists('po_bus');
    }
};
