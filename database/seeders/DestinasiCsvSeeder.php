<?php

namespace Database\Seeders;

use App\Models\DestinasiWisata;
use Illuminate\Database\Seeder;

class DestinasiCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('data/destinasi_wisata.csv');

        if (! file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");

            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file);

        if (! $header) {
            $this->command->error('CSV file is empty or invalid.');
            fclose($file);

            return;
        }

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 9) {
                continue;
            }

            // Map columns
            $kategori = trim($row[0] ?? '');
            $namaWisata = trim($row[1] ?? '');
            $lat = ! empty($row[2]) ? (float) $row[2] : null;
            $lng = ! empty($row[3]) ? (float) $row[3] : null;
            $alamat = trim($row[4] ?? '');
            $provinsi = trim($row[5] ?? '');
            $kotaKabupaten = trim($row[6] ?? '');
            $namaLengkap = trim($row[7] ?? '');
            $deskripsi = trim($row[8] ?? '');

            // Fallback description if missing or generic
            if (empty($deskripsi) || str_contains(strtolower($deskripsi), 'deskripsi tidak ditemukan')) {
                $location = ! empty($kotaKabupaten) ? $kotaKabupaten : (! empty($provinsi) ? $provinsi : 'Indonesia');
                $kategoriLabel = ! empty($kategori) ? ucwords($kategori) : 'Objek Wisata';
                $deskripsi = "{$namaWisata} merupakan destinasi {$kategoriLabel} yang terletak di kawasan {$location}. Tempat ini menawarkan panorama yang memukau dan daya tarik wisata unggulan bagi wisatawan domestik maupun mancanegara.";
            }

            // Aturan lokal default based on kategori
            $aturanLokal = match (strtolower($kategori)) {
                'candi', 'wisata religi' => 'Berpakaian sopan dan tertib, dilarang merusak batuan/struktur cagar budaya, dan hormati area peribadatan suci.',
                'gunung', 'wisata alam' => 'Dilarang membuang sampah sembarangan, dilarang menyalakan api unggun sembarangan, kenakan perlengkapan outdoor yang memadai.',
                'pantai', 'air terjun' => 'Perhatikan rambu batas aman kedalaman air, awasi anak-anak, dan jaga kebersihan biota laut/pesisir.',
                'museum' => 'Dilarang menyentuh koleksi tanpa izin, jaga ketenangan, dan dilarang menggunakan lampu flash kamera di area tertutup.',
                default => 'Patuhi protokol kebersihan, ketertiban umum, dan ikuti arahan petugas di lokasi.',
            };

            DestinasiWisata::updateOrCreate(
                ['nama_tempat' => $namaWisata],
                [
                    'deskripsi' => $deskripsi,
                    'aturan_lokal' => $aturanLokal,
                    'latitude' => $lat,
                    'longitude' => $lng,
                ]
            );

            $count++;
        }

        fclose($file);
        $this->command->info("Berhasil mengimpor {$count} data destinasi wisata ke database.");
    }
}
