<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use Illuminate\Contracts\Console\Kernel;

$k = Karyawan::find(8);
$po = PoBus::find(5) ?? PoBus::first();

if ($k && $po) {
    $supir = SupirPo::create([
        'po_id' => $po->id,
        'nama_supir' => $k->user->name,
        'kontak' => $k->user->no_hp,
        'peran' => 'Supir Utama',
        'keterangan' => 'Akun Login: '.$k->user->email,
    ]);

    $k->update([
        'po_id' => $po->id,
        'supir_po_id' => $supir->id,
        'posisi' => 'Supir Utama',
    ]);

    echo "Karyawan {$k->user->name} ({$k->user->email}) berhasil disambungkan ke PO {$po->nama_po} (Supir ID {$supir->id})!\n";
} else {
    echo "Karyawan atau PO tidak ditemukan.\n";
}
