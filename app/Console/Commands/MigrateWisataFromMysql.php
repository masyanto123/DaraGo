<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateWisataFromMysql extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'wisata:migrate-from-mysql
                            {--inspect : Hanya tampilkan struktur tabel MySQL, jangan migrasi}
                            {--chunk=200 : Jumlah baris per batch}
                            {--force : Jalankan tanpa konfirmasi}';

    /**
     * The console command description.
     */
    protected $description = 'Migrasi semua data wisata_indonesia_final dari MySQL lokal ke PostgreSQL (Neon DB)';

    public function handle(): int
    {
        // --- 1. Test koneksi MySQL ---
        $this->info('Menghubungkan ke MySQL lokal (darago)...');

        try {
            DB::connection('mysql_local')->getPdo();
            $this->info('Koneksi MySQL berhasil.');
        } catch (\Exception $e) {
            $this->error('Gagal koneksi MySQL: '.$e->getMessage());

            return self::FAILURE;
        }

        // --- 2. Cek apakah tabel ada ---
        $tables = DB::connection('mysql_local')->select('SHOW TABLES LIKE "wisata_indonesia_final"');
        if (empty($tables)) {
            $this->error('Tabel wisata_indonesia_final tidak ditemukan di database darago.');

            return self::FAILURE;
        }

        // --- 3. Tampilkan struktur kolom ---
        $columns = DB::connection('mysql_local')->select('DESCRIBE wisata_indonesia_final');
        $this->newLine();
        $this->info('Struktur tabel wisata_indonesia_final (MySQL):');
        $this->table(
            ['Field', 'Type', 'Null', 'Key', 'Default'],
            array_map(fn ($c) => [
                $c->Field,
                $c->Type,
                $c->Null,
                $c->Key,
                $c->Default ?? 'NULL',
            ], $columns)
        );

        $totalMySQL = DB::connection('mysql_local')->table('wisata_indonesia_final')->count();
        $this->newLine();
        $this->info("Total data di MySQL: {$totalMySQL} baris");

        if ($this->option('inspect')) {
            $samples = DB::connection('mysql_local')->table('wisata_indonesia_final')->limit(3)->get();
            $this->newLine();
            $this->info('Sample data (3 baris pertama):');
            foreach ($samples as $i => $row) {
                $this->line('  Row #'.($i + 1).': '.json_encode((array) $row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            return self::SUCCESS;
        }

        // --- 4. Konfirmasi sebelum migrasi ---
        if (! $this->option('force') && ! $this->confirm("Lanjutkan migrasi {$totalMySQL} baris ke PostgreSQL tabel wisata_indonesia?")) {
            $this->warn('Migrasi dibatalkan.');

            return self::SUCCESS;
        }

        // --- 5. Buat tabel wisata_indonesia di PostgreSQL jika belum ada ---
        $this->newLine();
        $this->info('Mempersiapkan tabel wisata_indonesia di PostgreSQL...');

        $columnDefs = DB::connection('mysql_local')->select('DESCRIBE wisata_indonesia_final');

        if (! Schema::connection('pgsql')->hasTable('wisata_indonesia')) {
            // Buat tabel baru dengan id serial sebagai PK
            $createSQL = $this->buildCreateTableSQL($columnDefs);
            DB::connection('pgsql')->statement($createSQL);
            $this->info('Tabel wisata_indonesia berhasil dibuat di PostgreSQL.');
        } elseif ($this->option('force')) {
            // Truncate supaya bersih sebelum import ulang
            DB::connection('pgsql')->statement('TRUNCATE TABLE wisata_indonesia RESTART IDENTITY CASCADE');
            $this->warn('Tabel wisata_indonesia di-truncate, data lama dihapus.');
        } else {
            $this->warn('Tabel wisata_indonesia sudah ada di PostgreSQL, data akan ditambahkan.');
        }

        // --- 6. Migrasi data dalam chunk ---
        $chunkSize = (int) $this->option('chunk');
        $this->newLine();
        $this->info("Memulai migrasi data ({$chunkSize} baris per batch)...");

        $migrated = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($totalMySQL);
        $bar->start();

        $offset = 0;
        while ($offset < $totalMySQL) {
            $rows = DB::connection('mysql_local')
                ->table('wisata_indonesia_final')
                ->offset($offset)
                ->limit($chunkSize)
                ->get();

            if ($rows->isEmpty()) {
                break;
            }

            $batch = [];
            foreach ($rows as $row) {
                $arr = (array) $row;

                foreach ($arr as $k => &$v) {
                    if ($v === '') {
                        $v = null;
                    }
                }
                unset($v);

                $batch[] = $arr;
            }

            try {
                // Insert langsung (tabel di PG punya id SERIAL sebagai PK)
                DB::connection('pgsql')->table('wisata_indonesia')->insert($batch);
                $migrated += count($batch);
            } catch (\Exception $e) {
                // Fallback: insert satu per satu
                foreach ($batch as $row) {
                    try {
                        DB::connection('pgsql')->table('wisata_indonesia')->insert($row);
                        $migrated++;
                    } catch (\Exception $err) {
                        $failed++;
                    }
                }
            }

            $bar->advance(count($rows));
            $offset += $chunkSize;
        }

        $bar->finish();
        $this->newLine(2);

        // --- 7. Verifikasi ---
        $totalPG = DB::connection('pgsql')->table('wisata_indonesia')->count();
        $this->info('Migrasi selesai!');
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Total MySQL (sumber)', $totalMySQL],
                ['Berhasil dimigrasikan', $migrated],
                ['Gagal / dilewati', $failed],
                ['Total di PostgreSQL sekarang', $totalPG],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Build CREATE TABLE SQL untuk PostgreSQL berdasarkan kolom MySQL.
     *
     * @param  array<object>  $columnDefs
     */
    private function buildCreateTableSQL(array $columnDefs): string
    {
        // Tambah id serial sebagai PK pertama
        $lines = ['    "id" BIGINT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY'];

        foreach ($columnDefs as $col) {
            $pgType = $this->mysqlTypeToPgsql($col->Type);
            $nullable = ''; // semua kolom MySQL nullable, ikuti itu

            $lines[] = "    \"{$col->Field}\" {$pgType}{$nullable}";
        }

        $colSQL = implode(",\n", $lines);

        return "CREATE TABLE IF NOT EXISTS \"wisata_indonesia\" (\n{$colSQL}\n)";
    }

    /**
     * Konversi tipe MySQL ke PostgreSQL.
     */
    private function mysqlTypeToPgsql(string $mysqlType): string
    {
        $type = strtolower($mysqlType);

        if (str_contains($type, 'bigint')) {
            return 'BIGINT';
        }
        if (str_contains($type, 'tinyint(1)')) {
            return 'BOOLEAN';
        }
        if (str_contains($type, 'tinyint')) {
            return 'SMALLINT';
        }
        if (str_contains($type, 'int')) {
            return 'INTEGER';
        }
        if (str_contains($type, 'float') || str_contains($type, 'double')) {
            return 'DOUBLE PRECISION';
        }
        if (str_contains($type, 'decimal') || str_contains($type, 'numeric')) {
            preg_match('/\([\d,]+\)/', $type, $m);

            return 'NUMERIC'.($m[0] ?? '');
        }
        if (str_contains($type, 'longtext') || str_contains($type, 'mediumtext')) {
            return 'TEXT';
        }
        if (str_contains($type, 'text')) {
            return 'TEXT';
        }
        if (str_contains($type, 'varchar')) {
            preg_match('/\((\d+)\)/', $type, $m);
            $len = $m[1] ?? 255;

            return "VARCHAR({$len})";
        }
        if (str_contains($type, 'datetime') || str_contains($type, 'timestamp')) {
            return 'TIMESTAMP';
        }
        if (str_contains($type, 'date')) {
            return 'DATE';
        }
        if (str_contains($type, 'time')) {
            return 'TIME';
        }
        if (str_contains($type, 'json')) {
            return 'JSONB';
        }
        if (str_contains($type, 'blob')) {
            return 'BYTEA';
        }
        if (str_contains($type, 'enum') || str_contains($type, 'set')) {
            return 'TEXT';
        }

        return 'TEXT';
    }
}
