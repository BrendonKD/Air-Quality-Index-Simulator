<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLocation extends Model
{
    use HasFactory;

    protected $table = 'sensor_locations'; // Add this if your table name is different
    protected $fillable = ['name', 'city', 'latitude', 'longitude', 'description']; // Add other fields as needed
    public $timestamps = false; // Or true, depending on your table structure

    public function aqiReadings()
    {
        return $this->hasMany(AqiReading::class, 'sensor_location_id');
    }
}