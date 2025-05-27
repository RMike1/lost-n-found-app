<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory, HasUlids;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class);
    }
}
