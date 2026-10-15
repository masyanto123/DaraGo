<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nama_instansi', 'kontak_darurat', 'catatan_medis'])]
class PesertaKlien extends Model
{
    use HasFactory;

    protected $table = 'peserta_klien';

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<ManifestPeserta, $this>
     */
    public function manifest(): HasMany
    {
        return $this->hasMany(ManifestPeserta::class, 'peserta_id');
    }

    /**
     * @return HasMany<FasilitasPeserta, $this>
     */
    public function fasilitas(): HasMany
    {
        return $this->hasMany(FasilitasPeserta::class, 'peserta_id');
    }
}
