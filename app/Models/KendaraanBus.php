<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['po_id', 'nomor_bus', 'tipe_bus', 'kapasitas', 'fasilitas'])]
class KendaraanBus extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_bus';

    /**
     * @return BelongsTo<PoBus, $this>
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(PoBus::class, 'po_id');
    }
}
