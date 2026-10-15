<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['trip_id', 'url_media', 'keterangan', 'waktu_upload'])]
class DokumentasiTrip extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_trip';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_upload' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<TripPerjalanan, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(TripPerjalanan::class, 'trip_id');
    }
}
