<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['trip_id', 'peserta_id', 'kategori', 'detail_fasilitas'])]
class FasilitasPeserta extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_peserta';

    /**
     * @return BelongsTo<TripPerjalanan, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(TripPerjalanan::class, 'trip_id');
    }

    /**
     * @return BelongsTo<PesertaKlien, $this>
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(PesertaKlien::class, 'peserta_id');
    }
}
