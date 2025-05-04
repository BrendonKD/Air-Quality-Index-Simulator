<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorLocation;

class SensorLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Colombo Fort Sensor', 'city' => 'Colombo', 'latitude' => 6.9339, 'longitude' => 79.8500, 'description' => 'Sensor located near Colombo Fort railway station'],
            ['name' => 'Galle Face Green Sensor', 'city' => 'Colombo', 'latitude' => 6.9271, 'longitude' => 79.8446, 'description' => 'Air quality monitoring near Galle Face Green'],
            ['name' => 'Borella Market Sensor', 'city' => 'Colombo', 'latitude' => 6.9147, 'longitude' => 79.8763, 'description' => 'Sensor near Borella public market'],
            ['name' => 'Nugegoda Junction Sensor', 'city' => 'Colombo', 'latitude' => 6.8723, 'longitude' => 79.8911, 'description' => 'Monitoring junction traffic emissions'],
            ['name' => 'Maradana Railway Sensor', 'city' => 'Colombo', 'latitude' => 6.9278, 'longitude' => 79.8614, 'description' => 'Located at Maradana Railway Station'],
            ['name' => 'Colombo University Sensor', 'city' => 'Colombo', 'latitude' => 6.9020, 'longitude' => 79.8610, 'description' => 'Installed near science faculty'],
            ['name' => 'Rajagiriya Flyover Sensor', 'city' => 'Colombo', 'latitude' => 6.9085, 'longitude' => 79.8915, 'description' => 'Traffic and emissions monitoring point'],
            ['name' => 'Town Hall Colombo Sensor', 'city' => 'Colombo', 'latitude' => 6.9173, 'longitude' => 79.8612, 'description' => 'Placed near Town Hall building'],
            ['name' => 'Pettah Bus Station Sensor', 'city' => 'Colombo', 'latitude' => 6.9396, 'longitude' => 79.8534, 'description' => 'High traffic bus stand emissions monitor'],
            ['name' => 'Bambalapitiya Railway Sensor', 'city' => 'Colombo', 'latitude' => 6.8881, 'longitude' => 79.8538, 'description' => 'Sensor beside coastal railway track'],
            ['name' => 'Dehiwala Zoo Sensor', 'city' => 'Colombo', 'latitude' => 6.8528, 'longitude' => 79.8650, 'description' => 'Environmental sensor near zoo premises'],
            ['name' => 'National Hospital Colombo Sensor', 'city' => 'Colombo', 'latitude' => 6.9212, 'longitude' => 79.8617, 'description' => 'Installed for hospital environment monitoring'],
            ['name' => 'Colombo South Teaching Hospital Sensor', 'city' => 'Colombo', 'latitude' => 6.8498, 'longitude' => 79.8688, 'description' => 'Health-sensitive zone monitoring'],
            ['name' => 'Thimbirigasyaya School Zone Sensor', 'city' => 'Colombo', 'latitude' => 6.8924, 'longitude' => 79.8762, 'description' => 'Sensor placed near school area'],
            ['name' => 'Orion City Sensor', 'city' => 'Colombo', 'latitude' => 6.9210, 'longitude' => 79.8764, 'description' => 'Tech park air monitoring point'],
            ['name' => 'Colombo Port Entrance Sensor', 'city' => 'Colombo', 'latitude' => 6.9545, 'longitude' => 79.8508, 'description' => 'Installed to monitor shipping emissions'],
            ['name' => 'Kollupitiya Junction Sensor', 'city' => 'Colombo', 'latitude' => 6.9083, 'longitude' => 79.8562, 'description' => 'Busy intersection sensor monitoring air quality'],
        ];

        foreach ($locations as $location) {
            SensorLocation::create($location);
        }
    }
}