<?php

namespace Database\Seeders;

use App\Models\KendaraanBus;
use App\Models\PoBus;
use App\Models\SupirPo;
use Illuminate\Database\Seeder;

class PoBusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poList = [
            [
                'nama_po' => 'PO Subur Jaya',
                'alamat' => 'Jl. Lingkar Luar No. 88, Rembang, Jawa Tengah',
                'kontak' => '0812-3456-7890',
                'detail' => 'Spesialis bus pariwisata premium Executive Class Jawa - Bali - Lombok. Fasilitas Audio Video on Demand (AVOD), Toilet, Reclining Seat, USB Charger, Dispenser.',
                'kendaraan' => [
                    [
                        'nomor_bus' => 'SUBUR JAYA 01 (K 7001 ED)',
                        'tipe_bus' => 'Jetbus 5 SHD',
                        'kapasitas' => 50,
                        'fasilitas' => 'AC, Reclining Seat 2-2, 2 TV LED, Audio Karaoke, Dispenser, USB Charger',
                    ],
                    [
                        'nomor_bus' => 'SUBUR JAYA 02 (K 7002 ED)',
                        'tipe_bus' => 'Jetbus 3+ HDD',
                        'kapasitas' => 48,
                        'fasilitas' => 'AC, Reclining Seat 2-2, Toilet, Audio Video Karaoke, Bantal & Selimut',
                    ],
                    [
                        'nomor_bus' => 'SUBUR JAYA 03 (K 7003 ED)',
                        'tipe_bus' => 'Legacy SR3 Suites Class',
                        'kapasitas' => 30,
                        'fasilitas' => 'Sleeper Seat, Personal Screen, Snack, Toilet, Free WiFi',
                    ],
                ],
                'supir' => [
                    [
                        'nama_supir' => 'Pak Joko Susilo',
                        'kontak' => '0812-9988-7711',
                        'nomor_sim' => 'SIM-B2-098231',
                        'keterangan' => 'Driver senior rute Jawa-Bali, berpengalaman 12 tahun.',
                    ],
                    [
                        'nama_supir' => 'Pak Bambang Pamungkas',
                        'kontak' => '0813-1122-3344',
                        'nomor_sim' => 'SIM-B2-887712',
                        'keterangan' => 'Driver pariwisata ramah, menguasai rute Malang - Bromo - Jogja.',
                    ],
                ],
            ],
            [
                'nama_po' => 'PO Haryanto',
                'alamat' => 'Jl. Lingkar Ngembal Kulon, Kudus, Jawa Tengah',
                'kontak' => '0813-8877-6655',
                'detail' => 'Pelayanan ramah berlandaskan syariah dan kedisiplinan tinggi. Armada VIP Class, AC Dingin, Bantal Selimut, Port Charger.',
                'kendaraan' => [
                    [
                        'nomor_bus' => 'HR 065 (B 7890 KAA)',
                        'tipe_bus' => 'Jetbus 3+ MHD',
                        'kapasitas' => 45,
                        'fasilitas' => 'AC, Karaoke, Reclining Seat, USB Charger, Smooking Area Belakang',
                    ],
                    [
                        'nomor_bus' => 'HR 115 (B 7891 KAA)',
                        'tipe_bus' => 'Jetbus 5 HDD',
                        'kapasitas' => 40,
                        'fasilitas' => 'AC, Toilet, Legrest, Audio Sound System Mantap',
                    ],
                ],
                'supir' => [
                    [
                        'nama_supir' => 'Mas Danang Tri',
                        'kontak' => '0857-4433-2211',
                        'nomor_sim' => 'SIM-B2-123456',
                        'keterangan' => 'Driver tangguh Pantura & Jalur Tol Trans Jawa.',
                    ],
                    [
                        'nama_supir' => 'Pak Agus Santoso',
                        'kontak' => '0858-6677-8899',
                        'nomor_sim' => 'SIM-B2-654321',
                        'keterangan' => 'Driver cadangan / tandem berpengalaman.',
                    ],
                ],
            ],
            [
                'nama_po' => 'PO Juragan 99 Trans',
                'alamat' => 'Jl. Raya Pakis No. 99, Malang, Jawa Timur',
                'kontak' => '0811-9999-0099',
                'detail' => 'Bus pariwisata & AKAP kelas sultan dengan kenyamanan maksimal. Luxury Bus, Sleeper Seat, First Class, Air Purifier, Coffee Maker, WiFi 5G.',
                'kendaraan' => [
                    [
                        'nomor_bus' => 'J99 Kanjuruhan (N 9999 JRG)',
                        'tipe_bus' => 'Jetbus 5 Dream Coach',
                        'kapasitas' => 22,
                        'fasilitas' => 'Capsule Sleeper, Massage Chair, Google TV, Headphone, Air Purifier',
                    ],
                    [
                        'nomor_bus' => 'J99 Bromo Executive (N 9901 JRG)',
                        'tipe_bus' => 'Jetbus 3+ UHD',
                        'kapasitas' => 38,
                        'fasilitas' => 'AC, Reclining Seat with Legrest, Toilet, Dispenser, Minibar',
                    ],
                ],
                'supir' => [
                    [
                        'nama_supir' => 'Mas Dimas Kurnia',
                        'kontak' => '0821-7788-9900',
                        'nomor_sim' => 'SIM-B2-990011',
                        'keterangan' => 'Driver tersertifikasi standar hospitality pariwisata bintang lima.',
                    ],
                ],
            ],
        ];

        foreach ($poList as $data) {
            $kendaraanData = $data['kendaraan'] ?? [];
            $supirData = $data['supir'] ?? [];
            unset($data['kendaraan'], $data['supir']);

            $po = PoBus::firstOrCreate(
                ['nama_po' => $data['nama_po']],
                $data
            );

            foreach ($kendaraanData as $k) {
                KendaraanBus::firstOrCreate(
                    [
                        'po_id' => $po->id,
                        'nomor_bus' => $k['nomor_bus'],
                    ],
                    $k
                );
            }

            foreach ($supirData as $s) {
                SupirPo::firstOrCreate(
                    [
                        'po_id' => $po->id,
                        'nama_supir' => $s['nama_supir'],
                    ],
                    $s
                );
            }
        }
    }
}
