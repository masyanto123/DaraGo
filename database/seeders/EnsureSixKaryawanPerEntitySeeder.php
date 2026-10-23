<?php

namespace Database\Seeders;

use App\Models\BiroPerjalanan;
use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnsureSixKaryawanPerEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Lengkapi Setiap Biro Perjalanan agar Memiliki 6 Karyawan / Crew
        $biros = BiroPerjalanan::all();
        $stafPoolNames = [
            'Rian Pratama', 'Siti Rahmawati', 'Dedi Setiawan', 'Maya Anggraini',
            'Bagas Saputra', 'Dewi Lestari', 'Fajar Ramadhan', 'Indah Permata',
            'Gilang Firmansyah', 'Putri Wulandari', 'Rizky Ananda', 'Nabila Maharani',
        ];
        $stafPositions = [
            'Tour Leader', 'Tour Guide', 'Medical Officer',
            'Logistik & Operasional', 'Customer Service', 'Admin Operasional Biro',
        ];

        foreach ($biros as $biro) {
            $currentCount = Karyawan::where('biro_id', $biro->id)->count();
            $needed = 6 - $currentCount;

            $this->command->info("🏢 Biro [{$biro->nama_biro}] memiliki {$currentCount} karyawan. Membutuhkan tambahan {$needed} karyawan.");

            for ($i = 0; $i < $needed; $i++) {
                DB::transaction(function () use ($biro, &$stafPoolNames, $stafPositions, $i, $currentCount) {
                    $name = array_shift($stafPoolNames) ?? ('Crew '.$biro->id.' '.($currentCount + $i + 1));
                    $slug = Str::slug($name, '.');
                    $email = $slug.'.'.$biro->id.'@biro.darago.id';
                    $posisi = $stafPositions[($currentCount + $i) % count($stafPositions)];

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => Hash::make('Password123!'),
                            'no_hp' => '0812'.rand(10000000, 99999999),
                            'role' => 'user',
                            'email_verified_at' => now(),
                        ]
                    );

                    Karyawan::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'biro_id' => $biro->id,
                            'po_id' => null,
                            'supir_po_id' => null,
                            'posisi' => $posisi,
                        ]
                    );

                    $this->command->info("  + Karyawan Biro ditambahkan: {$name} ({$posisi})");
                });
            }
        }

        // 2. Lengkapi Setiap PO Bus agar Memiliki 6 Supir / Driver
        $pos = PoBus::all();
        $driverPoolNames = [
            'Joko Susilo', 'Bambang Pamungkas', 'Dimas Kurnia', 'Rudi Hartono',
            'Eko Prasetyo', 'Wahyu Hidayat', 'Surya Saputra', 'Tri Cahyono',
            'Agung Wicaksono', 'Hadi Sucipto', 'Teguh Wibowo', 'Iwan Falsafah',
        ];
        $driverRoles = ['Supir Utama', 'Co-Supir'];

        foreach ($pos as $po) {
            $currentCount = Karyawan::where('po_id', $po->id)->count();
            $needed = 6 - $currentCount;

            $this->command->info("🚌 PO Bus [{$po->nama_po}] memiliki {$currentCount} supir. Membutuhkan tambahan {$needed} supir.");

            for ($i = 0; $i < $needed; $i++) {
                DB::transaction(function () use ($po, &$driverPoolNames, $driverRoles, $i, $currentCount) {
                    $name = array_shift($driverPoolNames) ?? ('Driver '.$po->id.' '.($currentCount + $i + 1));
                    $slug = Str::slug($name, '.');
                    $email = $slug.'.'.$po->id.'@driver.darago.id';
                    $peran = $driverRoles[($currentCount + $i) % 2];
                    $kontak = '0857'.rand(10000000, 99999999);

                    // Buat atau cari SupirPo
                    $supirPo = SupirPo::firstOrCreate(
                        [
                            'po_id' => $po->id,
                            'nama_supir' => $name,
                        ],
                        [
                            'kontak' => $kontak,
                            'peran' => $peran,
                            'nomor_sim' => 'SIM-B2-'.rand(100000, 999999),
                            'keterangan' => 'Driver profesional terdaftar',
                        ]
                    );

                    // Buat User login
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => Hash::make('Password123!'),
                            'no_hp' => $kontak,
                            'role' => 'user',
                            'email_verified_at' => now(),
                        ]
                    );

                    // Buat Karyawan
                    Karyawan::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'biro_id' => null,
                            'po_id' => $po->id,
                            'supir_po_id' => $supirPo->id,
                            'posisi' => $peran,
                        ]
                    );

                    $this->command->info("  + Driver PO ditambahkan: {$name} ({$peran})");
                });
            }
        }
    }
}
