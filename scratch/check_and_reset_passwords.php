<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();
echo 'Total users: '.$users->count().PHP_EOL;
foreach ($users as $user) {
    $matchesPassword123 = Hash::check('Password123!', $user->password);
    $matchesPassword = Hash::check('password', $user->password);
    echo "ID: {$user->id} | Email: {$user->email} | Role: {$user->role} | Password123!: ".($matchesPassword123 ? 'YES' : 'NO').' | password: '.($matchesPassword ? 'YES' : 'NO').PHP_EOL;
}
