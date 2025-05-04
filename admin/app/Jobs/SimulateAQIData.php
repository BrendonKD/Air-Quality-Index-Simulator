<?php

namespace App\Jobs;

use App\Models\SensorLocation;
use App\Models\AqiReading;
use App\Models\AlertThreshold;
use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class simulateAQIData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $location;

    /**
     * Create a new job instance.
     */
    public function __construct(SensorLocation $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $settings = \App\Models\SimulationSetting::first();
            if (!$settings || !$settings->is_running) {
                return;
            }

            $thresholds = AlertThreshold::where('is_active', true)->orderBy('min_value')->get();

            $minValue = max(0, $settings->aqi_baseline - $settings->fluctuation_range);
            $maxValue = min(500, $settings->aqi_baseline + $settings->fluctuation_range);

            $aqiValue = round(mt_rand($minValue * 10, $maxValue * 10) / 10, 1);

            $status = 'unknown';
            $matchingThreshold = null;

            foreach ($thresholds as $threshold) {
                if ($aqiValue >= $threshold->min_value && $aqiValue <= $threshold->max_value) {
                    $status = $threshold->level;
                    $matchingThreshold = $threshold;
                    break;
                }
            }

            AqiReading::create([
                'sensor_location_id' => $this->location->id,
                'value' => $aqiValue,
                'status' => $status,
                'is_simulated' => true,
            ]);

            if ($matchingThreshold && $matchingThreshold->level != 'good') {
                Alert::create([
                    'sensor_location_id' => $this->location->id,
                    'alert_threshold_id' => $matchingThreshold->id,
                    'aqi_value' => $aqiValue,
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error simulating AQI data for location: ' . $e->getMessage());
        }
    }
}
