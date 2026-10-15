<?php

namespace Database\Seeders;

use App\Models\ArmadaBus;
use App\Models\BiroPerjalanan;
use App\Models\DestinasiWisata;
use App\Models\DokumentasiTrip;
use App\Models\FasilitasPeserta;
use App\Models\JadwalAcara;
use App\Models\Karyawan;
use App\Models\LogLokasi;
use App\Models\ManifestPeserta;
use App\Models\PengumumanLivechat;
use App\Models\PesertaKlien;
use App\Models\TripPerjalanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds for DaraGo Master Data and Operations.
     */
    public function run(): void
    {
        // 1. BIRO PERJALANAN (Master Data)
        $biro1 = BiroPerjalanan::updateOrCreate(
            ['nama_biro' => 'PT DaraGo Tour & Travel Mandiri'],
            [
                'alamat' => 'Jl. Malioboro No. 45, Danurejan, Kota Yogyakarta, D.I. Yogyakarta 55213',
                'kontak' => '+62 812-3456-7890',
                'logo_url' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=150',
            ]
        );

        $biro2 = BiroPerjalanan::updateOrCreate(
            ['nama_biro' => 'Pesona Khatulistiwa Explorer'],
            [
                'alamat' => 'Jl. Asia Afrika No. 108, Sumur Bandung, Kota Bandung, Jawa Barat 40112',
                'kontak' => '+62 821-9876-5432',
                'logo_url' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=150',
            ]
        );

        $biro3 = BiroPerjalanan::updateOrCreate(
            ['nama_biro' => 'Bali Heritage & Adventure Tour'],
            [
                'alamat' => 'Jl. Sunset Road No. 88, Kuta, Kabupaten Badung, Bali 80361',
                'kontak' => '+62 813-1122-3344',
                'logo_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=150',
            ]
        );

        // 2. DESTINASI WISATA (Master Data)
        $destinasiList = [
            [
                'nama_tempat' => 'Candi Borobudur',
                'deskripsi' => 'Candi Buddha terbesar di dunia peninggalan Dinasti Syailendra berlatar pemandangan megah perbukitan Menoreh.',
                'aturan_lokal' => 'Wajib memakai sandal Upanat khusus untuk naik ke struktur candi. Dilarang membawa makanan ke area monumen.',
                'latitude' => -7.6078738,
                'longitude' => 110.2037513,
            ],
            [
                'nama_tempat' => 'Candi Prambanan & Ratu Boko',
                'deskripsi' => 'Kompleks candi Hindu terindah di Indonesia yang didedikasikan untuk Trimurti (Brahma, Wisnu, Siwa).',
                'aturan_lokal' => 'Dilarang memanjat batuan candi, gunakan jalur yang telah disediakan pemandu.',
                'latitude' => -7.7520206,
                'longitude' => 110.4914674,
            ],
            [
                'nama_tempat' => 'Gunung Bromo & Lautan Pasir',
                'deskripsi' => 'Kawasan kaldera Bromo Tengger Semeru dengan pemandangan matahari terbit spektakuler dan kawah aktif.',
                'aturan_lokal' => 'Kenakan pakaian hangat tebal. Dilarang mendekati bibir kawah melewati batas pagar pengaman.',
                'latitude' => -7.9424936,
                'longitude' => 112.9530122,
            ],
            [
                'nama_tempat' => 'Pantai Pandawa & Tebing Tanah Barak',
                'deskripsi' => 'Pantai pasir putih eksotis dengan tebing kapur megah dan patung Panca Pandawa di Pulau Dewata.',
                'aturan_lokal' => 'Perhatikan zona aman berenang dan hormati area suci persembahyangan lokal.',
                'latitude' => -8.8451372,
                'longitude' => 115.1866465,
            ],
            [
                'nama_tempat' => 'Taman Nasional Baluran & Savana Bekol',
                'deskripsi' => 'Kawasan suaka margasatwa dengan julukan Little Africa in Java yang dihuni banteng, rusa, dan merak.',
                'aturan_lokal' => 'Dilarang memberi makan satwa liar dan dilarang merokok di area padang rumput savana.',
                'latitude' => -7.8384218,
                'longitude' => 114.3934335,
            ],
            [
                'nama_tempat' => 'Kawah Ijen Blue Fire',
                'deskripsi' => 'Fenomena api biru alami abadi dan danau kawah asam toska terbesar di dunia.',
                'aturan_lokal' => 'Wajib memakai masker respirator gas belerang khusus. Pendakian hanya diperbolehkan bersama pemandu berlisensi.',
                'latitude' => -8.0583765,
                'longitude' => 114.2424773,
            ],
        ];

        $createdDestinasi = [];
        foreach ($destinasiList as $dest) {
            $createdDestinasi[] = DestinasiWisata::updateOrCreate(
                ['nama_tempat' => $dest['nama_tempat']],
                $dest
            );
        }

        // 3. USERS & KARYAWAN (Tour Leader & Supir)
        $crewData = [
            [
                'name' => 'Rian Hidayat, S.Par',
                'email' => 'rian.tourleader@darago.id',
                'no_hp' => '081223344551',
                'role' => 'admin',
                'posisi' => 'Tour Leader Utama',
                'biro_id' => $biro1->id,
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.guide@darago.id',
                'no_hp' => '081223344552',
                'role' => 'admin',
                'posisi' => 'Tour Coordinator & Guide',
                'biro_id' => $biro1->id,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.driver@darago.id',
                'no_hp' => '081223344553',
                'role' => 'user',
                'posisi' => 'Driver Eksekutif 1',
                'biro_id' => $biro1->id,
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.driver@darago.id',
                'no_hp' => '081223344554',
                'role' => 'user',
                'posisi' => 'Driver Eksekutif 2',
                'biro_id' => $biro1->id,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'fauzi.medic@darago.id',
                'no_hp' => '081223344555',
                'role' => 'user',
                'posisi' => 'Tour Leader & Medical Officer',
                'biro_id' => $biro2->id,
            ],
        ];

        $karyawanList = [];
        foreach ($crewData as $crew) {
            $user = User::updateOrCreate(
                ['email' => $crew['email']],
                [
                    'name' => $crew['name'],
                    'password' => Hash::make('Password123!'),
                    'role' => $crew['role'],
                    'no_hp' => $crew['no_hp'],
                    'email_verified_at' => now(),
                ]
            );

            $karyawanList[$crew['email']] = Karyawan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'biro_id' => $crew['biro_id'],
                    'posisi' => $crew['posisi'],
                ]
            );
        }

        // 4. USERS & PESERTA KLIEN (Master Data Peserta)
        $pesertaData = [
            [
                'name' => 'Ardiasnyah Candra Nugraha',
                'email' => 'ardiansyah.candra@gmail.com',
                'no_hp' => '085711223344',
                'nama_instansi' => 'SMA Negeri 1 Yogyakarta',
                'kontak_darurat' => '081233445566 (Ibu Ratna)',
                'catatan_medis' => 'Alergi seafood ringan, membawa inhaler asma',
            ],
            [
                'name' => 'Taufik Hidayat',
                'email' => 'taufikharkat@gmail.com',
                'no_hp' => '085722334455',
                'nama_instansi' => 'SMA Negeri 1 Yogyakarta',
                'kontak_darurat' => '081344556677 (Bpk. Bambang)',
                'catatan_medis' => 'Tidak ada riwayat penyakit',
            ],
            [
                'name' => 'Harkat Mahesa',
                'email' => 'harkat@gmail.com',
                'no_hp' => '085733445566',
                'nama_instansi' => 'Universitas Gadjah Mada',
                'kontak_darurat' => '081566778899 (Keluarga)',
                'catatan_medis' => 'Vegetarian, alergi dingin',
            ],
            [
                'name' => 'Ferguso Wijaya',
                'email' => 'ferguso@gmail.com',
                'no_hp' => '085744556677',
                'nama_instansi' => 'PT Telkom Indonesia Divre IV',
                'kontak_darurat' => '081677889900 (Kantor HRD)',
                'catatan_medis' => 'Sehat prima',
            ],
            [
                'name' => 'Apriadi Kusuma',
                'email' => 'apriadi00@gmail.com',
                'no_hp' => '085755667788',
                'nama_instansi' => 'PT Telkom Indonesia Divre IV',
                'kontak_darurat' => '081788990011 (Istri)',
                'catatan_medis' => 'Riwayat maag akut (wajib makan teratur)',
            ],
            [
                'name' => 'Nabila Putri Larasati',
                'email' => 'nabila.larasati@gmail.com',
                'no_hp' => '085766778899',
                'nama_instansi' => 'Komunitas Traveler Nusantara',
                'kontak_darurat' => '081899001122 (Kakak)',
                'catatan_medis' => 'Mabuk perjalanan darat jika duduk di baris belakang',
            ],
            [
                'name' => 'Dimas Arya Pratama',
                'email' => 'dimas.arya@gmail.com',
                'no_hp' => '085777889900',
                'nama_instansi' => 'RS Siloam Hospitality Group',
                'kontak_darurat' => '081900112233 (Orang Tua)',
                'catatan_medis' => 'Tidak ada catatan khusus',
            ],
        ];

        $pesertaCreated = [];
        foreach ($pesertaData as $peserta) {
            $u = User::updateOrCreate(
                ['email' => $peserta['email']],
                [
                    'name' => $peserta['name'],
                    'password' => Hash::make('Password123!'),
                    'role' => 'user',
                    'no_hp' => $peserta['no_hp'],
                    'email_verified_at' => now(),
                ]
            );

            $pesertaCreated[] = PesertaKlien::updateOrCreate(
                ['user_id' => $u->id],
                [
                    'nama_instansi' => $peserta['nama_instansi'],
                    'kontak_darurat' => $peserta['kontak_darurat'],
                    'catatan_medis' => $peserta['catatan_medis'],
                ]
            );
        }

        // 5. TRIP PERJALANAN (Operasional)
        $trip1 = TripPerjalanan::updateOrCreate(
            ['nama_kegiatan' => 'Study Tour & Eksplorasi Budaya Yogyakarta - Bali 2026'],
            [
                'biro_id' => $biro1->id,
                'tgl_mulai' => '2026-09-24',
                'tgl_selesai' => '2026-09-28',
                'status' => 'Ongoing',
            ]
        );

        $trip2 = TripPerjalanan::updateOrCreate(
            ['nama_kegiatan' => 'Corporate Gathering & Jeep Sunrise Bromo 2026'],
            [
                'biro_id' => $biro1->id,
                'tgl_mulai' => '2026-10-05',
                'tgl_selesai' => '2026-10-07',
                'status' => 'Upcoming',
            ]
        );

        $trip3 = TripPerjalanan::updateOrCreate(
            ['nama_kegiatan' => 'Ekspedisi Pesona Blue Fire Kawah Ijen & Baluran'],
            [
                'biro_id' => $biro2->id,
                'tgl_mulai' => '2026-10-18',
                'tgl_selesai' => '2026-10-21',
                'status' => 'Upcoming',
            ]
        );

        $trip4 = TripPerjalanan::updateOrCreate(
            ['nama_kegiatan' => 'Heritage Sunset Borobudur & Prambanan Tour'],
            [
                'biro_id' => $biro1->id,
                'tgl_mulai' => '2026-09-10',
                'tgl_selesai' => '2026-09-12',
                'status' => 'Completed',
            ]
        );

        // 6. ARMADA BUS
        $armada1 = ArmadaBus::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'nomor_bus' => 'BUS-01 (AB 7001 DA)',
            ],
            [
                'nama_po' => 'PO Subur Jaya Luxury Jetbus 5',
                'supir_id' => $karyawanList['budi.driver@darago.id']->id,
                'tour_leader_id' => $karyawanList['rian.tourleader@darago.id']->id,
            ]
        );

        $armada2 = ArmadaBus::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'nomor_bus' => 'BUS-02 (AB 7002 DA)',
            ],
            [
                'nama_po' => 'PO Haryanto Executive Avante H9',
                'supir_id' => $karyawanList['hendra.driver@darago.id']->id,
                'tour_leader_id' => $karyawanList['siti.guide@darago.id']->id,
            ]
        );

        // 7. MANIFEST PESERTA
        $seatNumbers = ['01A', '01B', '02A', '02B', '03A', '03B', '04A'];
        foreach ($pesertaCreated as $idx => $pk) {
            $armadaChosen = ($idx % 2 === 0) ? $armada1 : $armada2;
            ManifestPeserta::updateOrCreate(
                [
                    'trip_id' => $trip1->id,
                    'peserta_id' => $pk->id,
                ],
                [
                    'armada_id' => $armadaChosen->id,
                    'nomor_kursi' => $seatNumbers[$idx] ?? '10A',
                    'status_presensi' => true,
                ]
            );

            // 8. FASILITAS PESERTA
            FasilitasPeserta::updateOrCreate(
                [
                    'trip_id' => $trip1->id,
                    'peserta_id' => $pk->id,
                    'kategori' => 'Konsumsi & Akomodasi',
                ],
                [
                    'detail_fasilitas' => 'Hotel Grand Mercure Room Deluxe Twin Bed + Makan Prasmanan 3x Sehari',
                ]
            );
        }

        // 9. JADWAL ACARA (Itinerary)
        JadwalAcara::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'destinasi_id' => $createdDestinasi[0]->id, // Borobudur
                'kegiatan' => 'Kunjungan Edukasi & Sunrise Tour Candi Borobudur',
            ],
            [
                'waktu_mulai' => '2026-09-25 05:30:00',
                'waktu_selesai' => '2026-09-25 09:30:00',
            ]
        );

        JadwalAcara::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'destinasi_id' => $createdDestinasi[1]->id, // Prambanan
                'kegiatan' => 'Eksplorasi Sejarah Candi Prambanan & Pagelaran Ramayana',
            ],
            [
                'waktu_mulai' => '2026-09-25 14:00:00',
                'waktu_selesai' => '2026-09-25 18:00:00',
            ]
        );

        // 10. DOKUMENTASI TRIP
        DokumentasiTrip::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'url_media' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=600',
            ],
            [
                'keterangan' => 'Foto bersama seluruh peserta di pelataran Candi Borobudur Zona 1',
                'waktu_upload' => now()->subHours(4),
            ]
        );

        // 11. LOG LOKASI & PENGUMUMAN LIVECHAT
        $leaderUser = User::where('email', 'rian.tourleader@darago.id')->first();
        if ($leaderUser) {
            LogLokasi::updateOrCreate(
                [
                    'user_id' => $leaderUser->id,
                    'trip_id' => $trip1->id,
                    'tipe_entitas' => 'Tour Leader GPS Live',
                ],
                [
                    'latitude' => -7.6078738,
                    'longitude' => 110.2037513,
                    'timestamp' => now(),
                ]
            );
        }

        PengumumanLivechat::updateOrCreate(
            [
                'trip_id' => $trip1->id,
                'pesan_pengumuman' => 'Pemberitahuan: Seluruh peserta Bus 01 dan Bus 02 diharapkan berkumpul di meeting point pukul 13.30 WIB setelah istirahat makan siang.',
            ],
            [
                'armada_id' => $armada1->id,
                'waktu_dikirim' => now()->subMinutes(30),
            ]
        );
    }
}
