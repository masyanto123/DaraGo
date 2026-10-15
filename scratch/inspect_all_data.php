<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ArmadaBus;
use App\Models\BiroPerjalanan;
use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\TripPerjalanan;

echo 'Biro Perjalanan: '.BiroPerjalanan::count().PHP_EOL;
foreach (BiroPerjalanan::all() as $b) {
    echo "  - {$b->id}: {$b->nama_biro}\n";
}

echo 'Trip Perjalanan: '.TripPerjalanan::count().PHP_EOL;
foreach (TripPerjalanan::all() as $t) {
    echo "  - {$t->id}: {$t->nama_kegiatan} (Status: {$t->status})\n";
}

echo 'Armada Bus (Alokasi Trip): '.ArmadaBus::count().PHP_EOL;
foreach (ArmadaBus::all() as $a) {
    echo "  - {$a->id}: {$a->nomor_bus} (Trip: {$a->trip_id})\n";
}

echo 'PO Bus: '.PoBus::count().PHP_EOL;
foreach (PoBus::with(['kendaraan', 'supir'])->get() as $p) {
    echo "  - {$p->id}: {$p->nama_po} ({$p->kendaraan->count()} unit bus, {$p->supir->count()} supir)\n";
}

echo 'Karyawan: '.Karyawan::count().PHP_EOL;
foreach (Karyawan::with('user')->get() as $k) {
    echo "  - {$k->id}: {$k->user?->name} ({$k->posisi})\n";
}
