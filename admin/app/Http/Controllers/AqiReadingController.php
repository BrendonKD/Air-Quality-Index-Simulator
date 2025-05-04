<?php

namespace App\Http\Controllers;

use App\Models\AqiReading;
use App\Models\SensorLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AqiReadingController extends Controller
{
    /**
     * Display AQI readings
     */
    public function index()
    {
        $sensorLocations = SensorLocation::all();
        $latestReadings = AqiReading::with('sensorLocation')
            ->select('sensor_location_id', DB::raw('MAX(id) as id'))
            ->groupBy('sensor_location_id')
            ->get()
            ->map(function ($item) {
                return AqiReading::with('sensorLocation')->find($item->id);
            });
        
        return view('admin.simulation.readings', compact('sensorLocations', 'latestReadings'));
    }
    
    /**
     * Get latest readings for all sensors
     */
    public function getLatestReadings()
    {
        $latestReadings = AqiReading::with(['sensorLocation'])
            ->select('sensor_location_id', DB::raw('MAX(created_at) as max_date'))
            ->groupBy('sensor_location_id')
            ->get()
            ->map(function ($item) {
                return AqiReading::with('sensorLocation')
                    ->where('sensor_location_id', $item->sensor_location_id)
                    ->where('created_at', $item->max_date)
                    ->first();
            });
        
        return response()->json($latestReadings);
    }
    
    /**
     * Get readings history for a sensor
     */
    public function getReadingsHistory($sensorId)
    {
        $readings = AqiReading::where('sensor_location_id', $sensorId)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
            
        return response()->json($readings);
    }
}