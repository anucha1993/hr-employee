<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $fillable = [
        'district_name',
        'district_code',
        'amphur_code',
        'province_code',
        'zipcode',
    ];

    /**
     * Get the amphure that owns the district.
     */
    public function amphure(): BelongsTo
    {
        return $this->belongsTo(Amphure::class, 'amphur_code', 'amphur_code');
    }
    
    /**
     * Get the province that owns the district.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }
}
