<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Schema;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$columns = Schema::getColumnListing('destinasi_wisata');
echo "COLUMNS IN destinasi_wisata:\n";
print_r($columns);
