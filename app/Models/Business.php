<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected $casts = [
        'machinery_specs' => 'array',
    ];
}