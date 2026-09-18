<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MarketDemand extends Model
{
    use HasUuids;
    protected $table = 'market_demand';
    protected $guarded = [];
}