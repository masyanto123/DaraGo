<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['po_id', 'nama_supir', 'kontak', 'peran', 'nomor_sim', 'keterangan'])]
class SupirPo extends Model
{
    use HasFactory;

    protected $table = 'supir_po';

    /**
     * @return BelongsTo<PoBus, $this>
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(PoBus::class, 'po_id');
    }
}
