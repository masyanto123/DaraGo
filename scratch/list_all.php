<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\PoBus;
use Illuminate\Contracts\Console\Kernel;

echo "--- DAFTAR PO BUS & ANARAN SUPIR ---\n";
foreach (PoBus::with(['supir', 'kendaraan'])->get() as $po) {
    echo "PO: {$po->nama_po} (ID {$po->id})\n";
    echo '  - Total Unit: '.$po->kendaraan->count()."\n";
    echo '  - Total Supir: '.$po->supir->count()."\n";
    foreach ($po->supir as $s) {
        echo "    * {$s->nama_supir} ({$s->peran}) - {$s->kontak} | {$s->keterangan}\n";
    }
}

echo "\n--- DAFTAR KARYAWAN & CREW ---\n";
foreach (Karyawan::with(['user', 'biro', 'po', 'supirPo'])->get() as $k) {
    $afiliasi = $k->po ? "PO: {$k->po->nama_po}" : ($k->biro ? "Biro: {$k->biro->nama_biro}" : '-');
    echo "#{$k->id} | {$k->user?->name} ({$k->user?->email}) | Posisi: {$k->posisi} | Afiliasi: {$afiliasi}\n";
}
