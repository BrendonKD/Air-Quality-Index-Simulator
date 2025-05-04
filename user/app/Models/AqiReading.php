<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AqiReading extends Model
{
    use HasFactory;

    protected $table = 'aqi_readings'; // Add this line if your table name is different
    protected $fillable = ['sensor_location_id', 'value', 'status', 'created_at'];
    public $timestamps = true; // Explicitly set timestamps (if you have created_at and updated_at)

    public function sensorLocation()
    {
        return $this->belongsTo(SensorLocation::class, 'sensor_location_id');
    }
}