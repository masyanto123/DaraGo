<?php

use App\Models\DestinasiWisata;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo 'TEST DESTINASI COUNT: '.DestinasiWisata::count()."\n";
echo "TEST DASHBOARD DATA SUCCESS!\n";
