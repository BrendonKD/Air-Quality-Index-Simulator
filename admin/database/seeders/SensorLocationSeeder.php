<?php

namespace Database\Seeders;

use App\Models\SensorLocation;
use Illuminate\Database\Seeder;

class SensorLocationSeeder extends Seeder
{
    public function run(): void
    {
        $sensors = [
            [
                'name' => 'Colombo Fort Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9339,
                'longitude' => 79.8500,
                'description' => 'Sensor located near Colombo Fort railway station',
                'is_active' => true,
            ],
            [
                'name' => 'Galle Face Green Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9271,
                'longitude' => 79.8446,
                'description' => 'Air quality monitoring near Galle Face Green',
                'is_active' => true,
            ],
            [
                'name' => 'Borella Market Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9147,
                'longitude' => 79.8763,
                'description' => 'Sensor near Borella public market',
                'is_active' => true,
            ],
            [
                'name' => 'Nugegoda Junction Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8723,
                'longitude' => 79.8911,
                'description' => 'Monitoring junction traffic emissions',
                'is_active' => true,
            ],
            [
                'name' => 'Maradana Railway Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9278,
                'longitude' => 79.8614,
                'description' => 'Located at Maradana Railway Station',
                'is_active' => true,
            ],
            [
                'name' => 'Viharamahadevi Park Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9157,
                'longitude' => 79.8600,
                'description' => 'Air quality sensor in public park area',
                'is_active' => false,
            ],
            [
                'name' => 'Colombo University Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9020,
                'longitude' => 79.8610,
                'description' => 'Installed near science faculty',
                'is_active' => true,
            ],
            [
                'name' => 'Rajagiriya Flyover Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9085,
                'longitude' => 79.8915,
                'description' => 'Traffic and emissions monitoring point',
                'is_active' => true,
            ],
            [
                'name' => 'Town Hall Colombo Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9173,
                'longitude' => 79.8612,
                'description' => 'Placed near Town Hall building',
                'is_active' => true,
            ],
            [
                'name' => 'Independence Square Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9011,
                'longitude' => 79.8607,
                'description' => 'Installed near the walking path',
                'is_active' => false,
            ],
            [
                'name' => 'Pettah Bus Station Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9396,
                'longitude' => 79.8534,
                'description' => 'High traffic bus stand emissions monitor',
                'is_active' => true,
            ],
            [
                'name' => 'Bambalapitiya Railway Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8881,
                'longitude' => 79.8538,
                'description' => 'Sensor beside coastal railway track',
                'is_active' => true,
            ],
            [
                'name' => 'Dehiwala Zoo Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8528,
                'longitude' => 79.8650,
                'description' => 'Environmental sensor near zoo premises',
                'is_active' => true,
            ],
            [
                'name' => 'Wellawatte Beach Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8745,
                'longitude' => 79.8531,
                'description' => 'Monitoring coastal air quality',
                'is_active' => false,
            ],
            [
                'name' => 'National Hospital Colombo Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9212,
                'longitude' => 79.8617,
                'description' => 'Installed for hospital environment monitoring',
                'is_active' => true,
            ],
            [
                'name' => 'Colombo South Teaching Hospital Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8498,
                'longitude' => 79.8688,
                'description' => 'Health-sensitive zone monitoring',
                'is_active' => true,
            ],
            [
                'name' => 'Thimbirigasyaya School Zone Sensor',
                'city' => 'Colombo',
                'latitude' => 6.8924,
                'longitude' => 79.8762,
                'description' => 'Sensor placed near school area',
                'is_active' => true,
            ],
            [
                'name' => 'Orion City Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9210,
                'longitude' => 79.8764,
                'description' => 'Tech park air monitoring point',
                'is_active' => true,
            ],
            [
                'name' => 'Colombo Port Entrance Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9545,
                'longitude' => 79.8508,
                'description' => 'Installed to monitor shipping emissions',
                'is_active' => true,
            ],
            [
                'name' => 'Kollupitiya Junction Sensor',
                'city' => 'Colombo',
                'latitude' => 6.9083,
                'longitude' => 79.8562,
                'description' => 'Busy intersection sensor monitoring air quality',
                'is_active' => true,
            ],
        ];

        foreach ($sensors as $sensor) {
            SensorLocation::create($sensor);
        }
    }
}
