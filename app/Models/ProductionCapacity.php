<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductionCapacity extends Model
{
    use HasUuids;
    protected $table = 'production_capacity';
    protected $guarded = [];
}