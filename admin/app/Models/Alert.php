<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'sensor_location_id',
        'alert_threshold_id',
        'aqi_value',  
        'is_read'    
    ];
    
    // Set a default value when creating a new Alert
    protected static function booted()
    {
        static::creating(function ($alert) {
            if (!$alert->alert_threshold_id) {
                $alert->alert_threshold_id = 1;
            }
        });
    }
}