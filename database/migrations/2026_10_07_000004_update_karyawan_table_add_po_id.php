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
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('biro_id')->nullable()->change();
            $table->foreignId('po_id')->nullable()->after('biro_id')->constrained('po_bus')->nullOnDelete();
            $table->foreignId('supir_po_id')->nullable()->after('po_id')->constrained('supir_po')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['po_id']);
            $table->dropForeign(['supir_po_id']);
            $table->dropColumn(['po_id', 'supir_po_id']);
        });
    }
};
