<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$targetMigration = '2026_09_25_171356_migrate_destinasi_to_wisata_indonesia';
$filePath = database_path("migrations/{$targetMigration}.php");

echo "Membersihkan migrasi usang: {$targetMigration}...\n";

// 1. Hapus dari tabel migrations di database
$deletedRows = DB::table('migrations')->where('migration', $targetMigration)->delete();
echo "- Baris di tabel migrations terhapus: {$deletedRows}\n";

// 2. Hapus file php migrasi jika ada
if (file_exists($filePath)) {
    unlink($filePath);
    echo "- File migration {$targetMigration}.php berhasil dihapus dari folder database/migrations.\n";
} else {
    echo "- File migration tidak ditemukan.\n";
}

echo "Pembersihan migrasi selesai!\n";
