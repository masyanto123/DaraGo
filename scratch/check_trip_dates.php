<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TripPerjalanan;

foreach (TripPerjalanan::with(['armada.kendaraan', 'armada.tourLeader.user', 'armada.supirPo'])->get() as $t) {
    echo "Trip #{$t->id}: {$t->nama_kegiatan}\n";
    echo "  Dates: {$t->tgl_mulai?->format('Y-m-d')} s/d {$t->tgl_selesai?->format('Y-m-d')} (Status: {$t->status})\n";
    echo "  Biro ID: {$t->biro_id}\n";
    echo '  Armada ('.$t->armada->count()."):\n";
    foreach ($t->armada as $a) {
        echo "    - {$a->nomor_bus} | Kendaraan ID: {$a->kendaraan_id} | Supir: {$a->supir_po_id} | TL: {$a->tour_leader_id}\n";
    }
}
