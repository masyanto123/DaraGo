<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncSupirPoToKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua supir_po yang supir_po_id-nya belum ada di tabel karyawan
        $existingSupirPoIds = Karyawan::whereNotNull('supir_po_id')->pluck('supir_po_id')->toArray();
        $unlinkedSupirs = SupirPo::whereNotIn('id', $existingSupirPoIds)->get();

        $this->command->info('Ditemukan '.$unlinkedSupirs->count().' data supir yang belum terhubung ke Karyawan & Crew.');

        foreach ($unlinkedSupirs as $supir) {
            DB::transaction(function () use ($supir) {
                // Generate email unik berdasarkan nama
                $cleanName = Str::slug($supir->nama_supir, '.');
                $email = $cleanName.'@driver.darago.id';

                // Jika email sudah terpakai, tambahkan id supir
                if (User::where('email', $email)->exists()) {
                    $email = $cleanName.'.'.$supir->id.'@driver.darago.id';
                }

                // Buat user untuk supir
                $user = User::create([
                    'name' => $supir->nama_supir,
                    'email' => $email,
                    'password' => Hash::make('Password123!'),
                    'no_hp' => $supir->kontak,
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);

                // Tentukan posisi driver
                $posisi = $supir->peran ?: 'Supir Utama';

                // Update keterangan di supir_po jika belum ada
                if (empty($supir->keterangan)) {
                    $supir->update(['keterangan' => 'Akun Login: '.$email]);
                }

                // Buat data Karyawan
                Karyawan::create([
                    'user_id' => $user->id,
                    'biro_id' => null,
                    'po_id' => $supir->po_id,
                    'supir_po_id' => $supir->id,
                    'posisi' => $posisi,
                ]);

                $this->command->info("✓ Berhasil mensinkronkan: {$supir->nama_supir} (Email: {$email})");
            });
        }
    }
}
