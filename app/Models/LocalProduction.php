<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalProduction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'production_capacity';

    protected $fillable = [
        'district_id',
        'product_id',
        'business_id',
        'installed_capacity',
        'actual_production',
        'period',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}