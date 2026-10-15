<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

echo "Memulai penghapusan data master PO Bus, unit kendaraan, supir PO, dan armada trip...\n";

DB::transaction(function () {
    // 1. Putuskan relasi po_id & supir_po_id di tabel karyawan
    DB::table('karyawan')->update([
        'po_id' => null,
        'supir_po_id' => null,
    ]);

    // 2. Putuskan relasi supir_id di armada_bus jika ada
    DB::table('armada_bus')->update([
        'po_id' => null,
        'kendaraan_id' => null,
        'supir_po_id' => null,
    ]);

    // 3. Hapus data armada_bus (trip armada)
    $countArmadaTrip = DB::table('armada_bus')->delete();

    // 4. Hapus data supir_po
    $countSupir = DB::table('supir_po')->delete();

    // 5. Hapus data kendaraan_bus
    $countKendaraan = DB::table('kendaraan_bus')->delete();

    // 6. Hapus data po_bus
    $countPo = DB::table('po_bus')->delete();

    echo "Penghapusan berhasil!\n";
    echo "- Armada Bus Trip terhapus: {$countArmadaTrip}\n";
    echo "- Supir PO terhapus: {$countSupir}\n";
    echo "- Unit Kendaraan terhapus: {$countKendaraan}\n";
    echo "- Master PO Bus terhapus: {$countPo}\n";
});
