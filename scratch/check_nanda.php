<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

echo "=== USER / KARYAWAN MATCHING NANDA ===\n";
$users = User::where('name', 'ILIKE', '%nanda%')
    ->orWhere('email', 'ILIKE', '%nanda%')
    ->get();

if ($users->isEmpty()) {
    echo "Tidak ditemukan user dengan nama/email Nanda.\n";
    echo "Daftar seluruh User:\n";
    foreach (User::all() as $u) {
        echo "- ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role}\n";
    }
} else {
    foreach ($users as $u) {
        $k = Karyawan::where('user_id', $u->id)->first();
        echo "User ID {$u->id}: {$u->name} ({$u->email})\n";
        if ($k) {
            echo "  - Karyawan ID: {$k->id}\n";
            echo "  - Posisi: {$k->posisi}\n";
            echo '  - po_id: '.($k->po_id ?? 'NULL').' ('.($k->po?->nama_po ?? 'Tanpa PO').")\n";
            echo '  - supir_po_id: '.($k->supir_po_id ?? 'NULL')."\n";
            echo '  - biro_id: '.($k->biro_id ?? 'NULL').' ('.($k->biro?->nama_biro ?? 'Tanpa Biro').")\n";
        } else {
            echo "  - Karyawan record: TIDAK ADA\n";
        }
    }
}

echo "\n=== PO BUS LIST ===\n";
$pos = PoBus::all();
foreach ($pos as $p) {
    echo "PO ID {$p->id}: {$p->nama_po}\n";
    echo '  - Total Supir PO: '.$p->supir->count()."\n";
    foreach ($p->supir as $s) {
        echo "    * Supir ID {$s->id}: {$s->nama_supir} ({$s->peran}) - Kontak: {$s->kontak}\n";
    }
}

echo "\n=== DAFTAR SELURUH SUPIR PO ===\n";
foreach (SupirPo::with('po')->get() as $sp) {
    echo "- Supir ID {$sp->id}: {$sp->nama_supir} | PO ID {$sp->po_id} ({$sp->po?->nama_po}) | Peran: {$sp->peran}\n";
}
