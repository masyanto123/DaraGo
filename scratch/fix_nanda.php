<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use Illuminate\Contracts\Console\Kernel;

$k = Karyawan::whereHas('user', function ($q) {
    $q->where('email', 'nanda@gmail.com');
})->first();

$po = PoBus::where('nama_po', 'ILIKE', '%putra%')->first();

if ($k && $po) {
    // Buat record supir_po untuk Putra Abadi
    $supirPo = SupirPo::create([
        'po_id' => $po->id,
        'nama_supir' => $k->user->name,
        'kontak' => $k->user->no_hp ?? '08123456789',
        'peran' => 'Supir Utama',
        'keterangan' => 'Akun Login: '.$k->user->email,
    ]);

    // Update karyawan record
    $k->update([
        'po_id' => $po->id,
        'supir_po_id' => $supirPo->id,
        'posisi' => 'Supir Utama',
        'biro_id' => null,
    ]);

    echo "BERHASIL: test nanda (nanda@gmail.com) telah diubah menjadi Driver PO Bus dan disambungkan ke PO '{$po->nama_po}' (Supir ID {$supirPo->id})!\n";
} else {
    echo "Gagal: Karyawan nanda atau PO Putra Abadi tidak ditemukan.\n";
}
