<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
use Illuminate\Contracts\Console\Kernel;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\KendaraanBus;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$po = PoBus::where('nama_po', 'ILIKE', '%putra%')->first();

if (! $po) {
    $po = PoBus::create([
        'nama_po' => 'PO Putra Abadi',
        'alamat' => 'Jl. Muara Reja No. 88, Tegal, Jawa Tengah',
        'kontak' => '0812-9988-7766',
        'detail' => 'Layanan Bus Pariwisata Premium Executive Class & Sleeper Coach.',
    ]);
}

echo "Menambahkan Unit Kendaraan & Crew Driver ke PO: {$po->nama_po} (ID: {$po->id})...\n";

DB::transaction(function () use ($po) {
    // 1. TAMBAH UNIT ARMADA KENDARAAN BUS
    $units = [
        [
            'nomor_bus' => 'PUTRA ABADI 01 (G 7001 AB)',
            'tipe_bus' => 'Jetbus 5 SHD Premium',
            'kapasitas' => 50,
            'fasilitas' => 'AC Dingin, Reclining Seat 2-2, 2 TV LED, Audio Karaoke, Dispenser, USB Charger tiap seat',
        ],
        [
            'nomor_bus' => 'PUTRA ABADI 02 (G 7002 AB)',
            'tipe_bus' => 'Legacy SR3 Suites Class (Sleeper)',
            'kapasitas' => 32,
            'fasilitas' => 'Capsule Sleeper Seat, Personal Entertainment Screen, Massage Seat, Toilet, Free High Speed WiFi',
        ],
        [
            'nomor_bus' => 'PUTRA ABADI 03 (G 7003 AB)',
            'tipe_bus' => 'Jetbus 3+ HDD Executive',
            'kapasitas' => 48,
            'fasilitas' => 'AC, Toilet, Reclining Seat 2-2, Audio Karaoke System, Minibar & Coffee Maker',
        ],
        [
            'nomor_bus' => 'PUTRA ABADI 05 (G 7005 AB)',
            'tipe_bus' => 'Medium Bus Executive 35s',
            'kapasitas' => 35,
            'fasilitas' => 'AC, Reclining Seat 2-2, Audio Sound System, Coolbox, Bagasi Luas',
        ],
    ];

    foreach ($units as $uData) {
        KendaraanBus::firstOrCreate(
            [
                'po_id' => $po->id,
                'nomor_bus' => $uData['nomor_bus'],
            ],
            $uData
        );
    }
    echo "- 4 Unit Armada Kendaraan berhasil ditambahkan.\n";

    // 2. TAMBAH KARYAWAN & CREW DRIVER
    $drivers = [
        [
            'name' => 'Pak Nanda Kurniawan',
            'email' => 'nandakurniawan@putraabadi.id',
            'no_hp' => '0812-9988-1122',
            'peran' => 'Supir Utama',
        ],
        [
            'name' => 'Mas Slamet Riyadi',
            'email' => 'slamet@putraabadi.id',
            'no_hp' => '0813-8877-3344',
            'peran' => 'Supir Utama',
        ],
        [
            'name' => 'Pak Agus Setiawan',
            'email' => 'agus.setiawan@putraabadi.id',
            'no_hp' => '0857-1122-5566',
            'peran' => 'Co-Supir',
        ],
        [
            'name' => 'Mas Bayu Prakoso',
            'email' => 'bayuprakoso@putraabadi.id',
            'no_hp' => '0858-6677-9900',
            'peran' => 'Co-Supir',
        ],
    ];

    foreach ($drivers as $d) {
        $user = User::updateOrCreate(
            ['email' => $d['email']],
            [
                'name' => $d['name'],
                'password' => Hash::make('Password123!'),
                'no_hp' => $d['no_hp'],
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $supirPo = SupirPo::create([
            'po_id' => $po->id,
            'nama_supir' => $d['name'],
            'kontak' => $d['no_hp'],
            'peran' => $d['peran'],
            'keterangan' => 'Akun Login: '.$d['email'],
        ]);

        Karyawan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'po_id' => $po->id,
                'supir_po_id' => $supirPo->id,
                'posisi' => $d['peran'],
                'biro_id' => null,
            ]
        );
    }
    echo "- 4 Driver Crew (2 Supir Utama & 2 Co-Supir) berhasil ditambahkan & tersambung.\n";
});

echo "Selesai!\n";
