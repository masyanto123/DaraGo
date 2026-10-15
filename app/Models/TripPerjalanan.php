<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['biro_id', 'nama_kegiatan', 'tgl_mulai', 'tgl_selesai', 'status'])]
class TripPerjalanan extends Model
{
    use HasFactory;

    protected $table = 'trip_perjalanan';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_mulai' => 'date',
            'tgl_selesai' => 'date',
        ];
    }

    /**
     * @return BelongsTo<BiroPerjalanan, $this>
     */
    public function biro(): BelongsTo
    {
        return $this->belongsTo(BiroPerjalanan::class, 'biro_id');
    }

    /**
     * @return HasMany<ArmadaBus, $this>
     */
    public function armada(): HasMany
    {
        return $this->hasMany(ArmadaBus::class, 'trip_id');
    }

    /**
     * @return HasMany<ManifestPeserta, $this>
     */
    public function manifest(): HasMany
    {
        return $this->hasMany(ManifestPeserta::class, 'trip_id');
    }

    /**
     * @return HasMany<JadwalAcara, $this>
     */
    public function jadwalAcara(): HasMany
    {
        return $this->hasMany(JadwalAcara::class, 'trip_id');
    }

    /**
     * @return HasMany<FasilitasPeserta, $this>
     */
    public function fasilitas(): HasMany
    {
        return $this->hasMany(FasilitasPeserta::class, 'trip_id');
    }

    /**
     * @return HasMany<DokumentasiTrip, $this>
     */
    public function dokumentasi(): HasMany
    {
        return $this->hasMany(DokumentasiTrip::class, 'trip_id');
    }
}
