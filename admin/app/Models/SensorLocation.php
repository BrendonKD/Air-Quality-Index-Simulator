<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'city', 'latitude', 'longitude', 'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}