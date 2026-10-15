<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['trip_id', 'po_id', 'kendaraan_id', 'nama_po', 'nomor_bus', 'supir_po_id', 'nama_supir', 'supir_id', 'tour_leader_id'])]
class ArmadaBus extends Model
{
    use HasFactory;

    protected $table = 'armada_bus';

    /**
     * @return BelongsTo<TripPerjalanan, $this>
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(TripPerjalanan::class, 'trip_id');
    }

    /**
     * @return BelongsTo<PoBus, $this>
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(PoBus::class, 'po_id');
    }

    /**
     * @return BelongsTo<KendaraanBus, $this>
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(KendaraanBus::class, 'kendaraan_id');
    }

    /**
     * @return BelongsTo<SupirPo, $this>
     */
    public function supirPo(): BelongsTo
    {
        return $this->belongsTo(SupirPo::class, 'supir_po_id');
    }

    /**
     * @return BelongsTo<Karyawan, $this>
     */
    public function supir(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'supir_id');
    }

    /**
     * @return BelongsTo<Karyawan, $this>
     */
    public function tourLeader(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'tour_leader_id');
    }

    /**
     * @return HasMany<ManifestPeserta, $this>
     */
    public function manifest(): HasMany
    {
        return $this->hasMany(ManifestPeserta::class, 'armada_id');
    }

    /**
     * Mendapatkan nama supir terdaftar (baik dari Supir PO, nama_supir, atau Karyawan)
     */
    public function getNamaDriverAttribute(): string
    {
        return $this->supirPo?->nama_supir
            ?? $this->nama_supir
            ?? $this->supir?->user?->name
            ?? 'Belum ditugaskan';
    }
}
