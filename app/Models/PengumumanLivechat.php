<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['trip_id', 'armada_id', 'pesan_pengumuman', 'waktu_dikirim'])]
class PengumumanLivechat extends Model
{
    use HasFactory;

    protected $table = 'pengumuman_livechat';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_dikirim' => 'datetime',
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
}
