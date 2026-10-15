<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_biro', 'alamat', 'kontak', 'logo_url'])]
class BiroPerjalanan extends Model
{
    use HasFactory;

    protected $table = 'biro_perjalanan';

    /**
     * @return HasMany<Karyawan, $this>
     */
    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'biro_id');
    }

    /**
     * @return HasMany<TripPerjalanan, $this>
     */
    public function tripPerjalanan(): HasMany
    {
        return $this->hasMany(TripPerjalanan::class, 'biro_id');
    }
}
