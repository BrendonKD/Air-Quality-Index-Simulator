<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AqiReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_location_id',
        'value',
        'status',
        'is_simulated',
    ];

    protected $casts = [
        'value' => 'float',
        'is_simulated' => 'boolean',
    ];

    public function sensorLocation()
    {
        return $this->belongsTo(SensorLocation::class);
    }
}