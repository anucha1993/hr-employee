<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'province_name',
        'province_code',
    ];

    /**
     * Get the amphures for the province.
     */
    public function amphures(): HasMany
    {
        return $this->hasMany(Amphure::class, 'province_code', 'province_code');
    }
    
    /**
     * Get the districts for the province.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'province_code', 'province_code');
    }
}
