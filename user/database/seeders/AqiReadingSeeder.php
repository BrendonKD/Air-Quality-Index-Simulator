<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorLocation;
use App\Models\AqiReading;
use Carbon\Carbon;

class AqiReadingSeeder extends Seeder
{
    public function run(): void
    {
        
        $calculateAQI = function ($pm25) {
            if ($pm25 <= 12) {
                return round((50 / 12) * $pm25);
            } elseif ($pm25 <= 35.4) {
                return round(((100 - 51) / (35.4 - 12.1)) * ($pm25 - 12.1) + 51);
            } elseif ($pm25 <= 55.4) {
                return round(((150 - 101) / (55.4 - 35.5)) * ($pm25 - 35.5) + 101);
            } elseif ($pm25 <= 150.4) {
                return round(((200 - 151) / (150.4 - 55.5)) * ($pm25 - 55.5) + 151);
            } elseif ($pm25 <= 250.4) {
                return round(((300 - 201) / (250.4 - 150.5)) * ($pm25 - 150.5) + 201);
            } else {
                return round(((500 - 301) / (500.4 - 250.5)) * ($pm25 - 250.5) + 301);
            }
        };

        
        $getAQICategory = function ($aqi) {
            if ($aqi <= 50) return 'Good';
            if ($aqi <= 100) return 'Moderate';
            if ($aqi <= 150) return 'Unhealthy for Sensitive Groups';
            if ($aqi <= 200) return 'Unhealthy';
            if ($aqi <= 300) return 'Very Unhealthy';
            return 'Hazardous';
        };

        
        $sensorLocations = SensorLocation::all();

        foreach ($sensorLocations as $sensor) {
            $basePm25 = 5 + rand(0, 50);
            $aqi = $calculateAQI($basePm25);
            $status = $getAQICategory($aqi);

            AqiReading::create([
                'sensor_location_id' => $sensor->id,
                'value' => $aqi,
                'status' => $status,
                'created_at' => Carbon::parse('2025-04-27 00:29:00'),
            ]);

            
            $dates = [
                '2025-04-20', '2025-04-21', '2025-04-22', '2025-04-23',
                '2025-04-24', '2025-04-25', '2025-04-26'
            ];

            foreach ($dates as $date) {
                $historicalPm25 = $basePm25 * (0.9 + (rand(0, 20) / 100)); 
                $historicalAqi = $calculateAQI($historicalPm25);
                $historicalStatus = $getAQICategory($historicalAqi);

                AqiReading::create([
                    'sensor_location_id' => $sensor->id,
                    'value' => $historicalAqi,
                    'status' => $historicalStatus,
                    'created_at' => Carbon::parse($date . ' 00:00:00'),
                ]);
            }
        }
    }
}