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
        Schema::table('supir_po', function (Blueprint $table) {
            $table->string('peran', 50)->default('Supir Utama')->after('nama_supir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supir_po', function (Blueprint $table) {
            $table->dropColumn('peran');
        });
    }
};
