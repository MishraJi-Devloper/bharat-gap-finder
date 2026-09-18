<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ManufacturingGap extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected $casts = [
        'score_breakdown' => 'array',
        'barriers' => 'array',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}