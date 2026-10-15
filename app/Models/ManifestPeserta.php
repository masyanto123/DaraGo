<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['trip_id', 'armada_id', 'peserta_id', 'nomor_kursi', 'status_presensi'])]
class ManifestPeserta extends Model
{
    use HasFactory;

    protected $table = 'manifest_peserta';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_presensi' => 'boolean',
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
     * @return BelongsTo<ArmadaBus, $this>
     */
    public function armada(): BelongsTo
    {
        return $this->belongsTo(ArmadaBus::class, 'armada_id');
    }

    /**
     * @return BelongsTo<PesertaKlien, $this>
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(PesertaKlien::class, 'peserta_id');
    }
}
