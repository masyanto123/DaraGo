<?php

use App\Models\ArmadaBus;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo 'ARMADA COUNT: '.ArmadaBus::count()."\n";
foreach (ArmadaBus::all() as $a) {
    echo "ID: {$a->id}, PO: {$a->nama_po}, Bus: {$a->nomor_bus}\n";
}
