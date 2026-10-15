<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\DestinasiWisata;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
echo "Tables in PostgreSQL:\n";
foreach ($tables as $t) {
    echo '- '.$t->table_name."\n";
}

echo "\nDestinasiWisata count: ".DestinasiWisata::count()."\n";

// Check if MySQL connection is available
try {
    $mysqlCount = DB::connection('mysql_local')->table('wisata_indonesia_final')->count();
    echo 'MySQL local (wisata_indonesia_final) count: '.$mysqlCount."\n";
} catch (Exception $e) {
    echo 'MySQL local error: '.$e->getMessage()."\n";
}

// Check CSV file if any
$csvPath = database_path('seeders/destinasi.csv');
if (file_exists($csvPath)) {
    echo 'destinasi.csv size: '.filesize($csvPath)." bytes\n";
} else {
    echo "destinasi.csv does not exist.\n";
}
