<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorLocation;
use Carbon\Carbon;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorLocation;
use Carbon\Carbon;

class AQIController extends Controller
{
    public function showMap()
    {
        return view('map');
    }

    public function getSensorsAqi()
    {
        $sensors = SensorLocation::with(['aqiReadings' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->get();

        return response()->json($sensors->map(function ($sensor) {
            $realTimeReading = $sensor->aqiReadings->last();
            // Ensure historical readings are limited to the last 7 days
            $historicalReadings = $sensor->aqiReadings
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->values()
                ->toArray();

            // Map historical readings to the desired format
            $historicalData = array_map(function ($reading) {
                return [
                    'date' => Carbon::parse($reading['created_at'])->toDateString(),
                    'aqi' => $reading['value'],
                    'day' => Carbon::parse($reading['created_at'])->format('l'),
                ];
            }, $historicalReadings);

            return [
                'sensor_id' => $sensor->id, // Include sensor_id
                'name' => $sensor->name,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'description' => $sensor->description,
                'realtime_aqi' => $realTimeReading ? $realTimeReading->value : null, // simplified
                'sensor_name' => "AQD{$sensor->id}C70",
                'realtime_date' => $realTimeReading ? $realTimeReading->created_at->toIso8601String() : null,
                'historicalData' => $historicalData,
            ];
        }));
    }

    public function getSensorHistory($sensorId)
    {
        $history = SensorLocation::findOrFail($sensorId)->aqiReadings()
            ->orderBy('created_at')
            ->get()
            ->map(function ($reading) {
                return [
                    'date' => $reading->created_at->toDateTimeString(),
                    'aqi' => $reading->value,
                ];
            });

        return response()->json($history);
    }
}