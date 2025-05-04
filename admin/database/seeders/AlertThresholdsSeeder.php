<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlertThreshold;

class AlertThresholdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AlertThreshold::create([
            'level' => 'Good',
            'min_value' => 0,
            'max_value' => 50,
            'color_code' => '#00E400',
            'description' => 'Air quality is satisfactory, and air pollution poses little or no risk.',
            'is_active' => true,
        ]);
        
        AlertThreshold::create([
            'level' => 'Moderate',
            'min_value' => 51,
            'max_value' => 100,
            'color_code' => '#FFFF00',
            'description' => 'Air quality is acceptable. However, there may be a risk for some people, particularly those who are unusually sensitive to air pollution.',
            'is_active' => true,
        ]);
        
        AlertThreshold::create([
            'level' => 'Unhealthy for Sensitive Groups',
            'min_value' => 101,
            'max_value' => 150,
            'color_code' => '#FF7E00',
            'description' => 'Members of sensitive groups may experience health effects. The general public is less likely to be affected.',
            'is_active' => true,
        ]);
        
        AlertThreshold::create([
            'level' => 'Unhealthy',
            'min_value' => 151,
            'max_value' => 200,
            'color_code' => '#FF0000',
            'description' => 'Some members of the general public may experience health effects; members of sensitive groups may experience more serious health effects.',
            'is_active' => true,
        ]);
        
        AlertThreshold::create([
            'level' => 'Very Unhealthy',
            'min_value' => 201,
            'max_value' => 300,
            'color_code' => '#99004C',
            'description' => 'Health alert: The risk of health effects is increased for everyone.',
            'is_active' => true,
        ]);
        
        AlertThreshold::create([
            'level' => 'Hazardous',
            'min_value' => 301,
            'max_value' => 500,
            'color_code' => '#7E0023',
            'description' => 'Health warning of emergency conditions: everyone is more likely to be affected.',
            'is_active' => true,
        ]);
    }
}