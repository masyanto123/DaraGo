<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_po', 'alamat', 'kontak', 'detail'])]
class PoBus extends Model
{
    use HasFactory;

    protected $table = 'po_bus';

    /**
     * @return HasMany<KendaraanBus, $this>
     */
    public function kendaraan(): HasMany
    {
        return $this->hasMany(KendaraanBus::class, 'po_id');
    }

    /**
     * @return HasMany<SupirPo, $this>
     */
    public function supir(): HasMany
    {
        return $this->hasMany(SupirPo::class, 'po_id');
    }

    /**
     * @return HasMany<ArmadaBus, $this>
     */
    public function armadaTrip(): HasMany
    {
        return $this->hasMany(ArmadaBus::class, 'po_id');
    }
}
