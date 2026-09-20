<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasUuids;

    protected $guarded = [];

    /**
     * Get the businesses registered under this district.
     */
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    /**
     * Get the manufacturing gaps identified in this district.
     */
    public function manufacturingGaps(): HasMany
    {
        return $this->hasMany(ManufacturingGap::class);
    }
}