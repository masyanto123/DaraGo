<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@darago.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Password123!'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@darago.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
