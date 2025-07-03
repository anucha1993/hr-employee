<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Amphure extends Model
{
    protected $fillable = [
        'amphur_name',
        'amphur_code',
        'province_code',
    ];

    /**
     * Get the province that owns the amphure.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    /**
     * Get the districts for the amphure.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'amphur_code', 'amphur_code');
    }
}
