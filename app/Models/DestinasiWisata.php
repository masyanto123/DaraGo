<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nama_tempat',
    'deskripsi',
    'aturan_lokal',
    'latitude',
    'longitude',
])]
class DestinasiWisata extends Model
{
    use HasFactory;

    /**
     * Tabel database destinasi wisata.
     */
    protected $table = 'destinasi_wisata';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'double',
            'longitude' => 'double',
        ];
    }

    /**
     * Accessor: nama_wisata → alias ke nama_tempat agar kompatibel.
     */
    public function getNamaWisataAttribute(): string
    {
        return $this->attributes['nama_tempat'] ?? '';
    }

    /**
     * @return HasMany<JadwalAcara, $this>
     */
    public function jadwalAcara(): HasMany
    {
        return $this->hasMany(JadwalAcara::class, 'destinasi_id');
    }
}
