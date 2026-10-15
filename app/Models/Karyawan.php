<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'biro_id', 'po_id', 'supir_po_id', 'posisi'])]
class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<BiroPerjalanan, $this>
     */
    public function biro(): BelongsTo
    {
        return $this->belongsTo(BiroPerjalanan::class, 'biro_id');
    }

    /**
     * @return BelongsTo<PoBus, $this>
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(PoBus::class, 'po_id');
    }

    /**
     * @return BelongsTo<SupirPo, $this>
     */
    public function supirPo(): BelongsTo
    {
        return $this->belongsTo(SupirPo::class, 'supir_po_id');
    }

    /**
     * @return HasMany<ArmadaBus, $this>
     */
    public function armadaSebagaiSupir(): HasMany
    {
        return $this->hasMany(ArmadaBus::class, 'supir_id');
    }

    /**
     * @return HasMany<ArmadaBus, $this>
     */
    public function armadaSebagaiTourLeader(): HasMany
    {
        return $this->hasMany(ArmadaBus::class, 'tour_leader_id');
    }
}
