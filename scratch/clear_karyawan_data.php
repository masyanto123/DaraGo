<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

echo "Memulai penghapusan data master Karyawan & Crew...\n";

DB::transaction(function () {
    $karyawans = Karyawan::with('user')->get();
    $deletedCount = 0;

    foreach ($karyawans as $k) {
        $user = $k->user;
        $k->delete();

        if ($user && $user->role !== 'superadmin') {
            $user->delete();
        }
        $deletedCount++;
    }

    echo "Penghapusan Karyawan & Crew berhasil!\n";
    echo "- Total Karyawan & Crew terhapus: {$deletedCount}\n";
    echo '- Sisa Karyawan & Crew di database: '.Karyawan::count()."\n";
});
