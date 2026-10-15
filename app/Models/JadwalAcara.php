<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'trip_id',
    'destinasi_id',
    'waktu_mulai',
    'waktu_selesai',
    'kegiatan',
    'latitude',
    'longitude',
])]
class JadwalAcara extends Model
{
    use HasFactory;

    protected $table = 'jadwal_acara';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'latitude' => 'double',
            'longitude' => 'double',
        ];
    }

    /**
     * @return BelongsTo<TripPerjalanan, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(TripPerjalanan::class, 'trip_id');
    }

    /**
     * @return BelongsTo<DestinasiWisata, $this>
     */
    public function destinasi(): BelongsTo
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }
}
