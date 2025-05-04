<?php

namespace App\Http\Controllers;

use App\Models\SimulationSetting;
use App\Models\SensorLocation;
use App\Models\AqiReading;
use App\Models\AlertThreshold;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller
{
    /**
     * Display simulation settings page
     */
    public function index()
    {
        $settings = SimulationSetting::first() ?? SimulationSetting::create([
            'aqi_baseline' => 50.0,
            'fluctuation_range' => 20.0,
            'frequency_seconds' => 300,
            'is_running' => false,
        ]);
        
        $thresholds = AlertThreshold::orderBy('min_value')->get();
        $sensorCount = SensorLocation::count();
        $locations = SensorLocation::all();
        
        //changed simulation -- AQIgen --correct
        return view('admin.simulation.AQIgen', compact('settings', 'thresholds', 'sensorCount' , 'locations'));
    }
    
    /**
     * Update simulation settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aqi_baseline' => 'required|numeric|min:0|max:500',
            'fluctuation_range' => 'required|numeric|min:0|max:100',
            'frequency_seconds' => 'required|integer|min:30|max:3600',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $settings = SimulationSetting::first() ?? new SimulationSetting();
            $settings->aqi_baseline = $request->aqi_baseline;
            $settings->fluctuation_range = $request->fluctuation_range;
            $settings->frequency_seconds = $request->frequency_seconds;
            $settings->save();
            
            return redirect()->back()->with('success', 'Simulation settings updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update simulation settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update settings. Please try again.');
        }
    }
    public function startSimulation()
    {
        try {
            $sensorCount = SensorLocation::count();
            if ($sensorCount === 0) {
                return redirect()->back()->with('error', 'Cannot start simulation. No sensor locations are configured.');
            }
            
            $settings = SimulationSetting::first();
            if (!$settings) {
                return redirect()->back()->with('error', 'Simulation settings not found');
            }
            
            $settings->is_running = true;
            $settings->save();
            
            $result = $this->generateReadings();
            if ($result['status'] === 'error') {
                return redirect()->back()->with('error', $result['message']);
            }
            
            return redirect()->back()->with('success', 'Simulation started successfully');
        } catch (\Exception $e) {
            Log::error('Failed to start simulation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to start simulation. Please try again.');
        }
    }
    
    /**
     * Stop simulation
     */
    public function stopSimulation()
    {
        try {
            $settings = SimulationSetting::first();
            if (!$settings) {
                return redirect()->back()->with('error', 'Simulation settings not found');
            }
            
            $settings->is_running = false;
            $settings->save();
            
            return redirect()->back()->with('success', 'Simulation stopped successfully');
        } catch (\Exception $e) {
            Log::error('Failed to stop simulation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to stop simulation. Please try again.');
        }
    }
    
    /**
     * Generate simulated readings for all sensors
     */
    public function generateReadings()
    {
        try {
            $settings = SimulationSetting::first();
            if (!$settings || !$settings->is_running) {
                return [
                    'status' => 'error', 
                    'message' => 'Simulation is not running'
                ];
            }
            
            $sensorLocations = SensorLocation::all();
            if ($sensorLocations->isEmpty()) {
                return [
                    'status' => 'error', 
                    'message' => 'No sensor locations found'
                ];
            }
            
            $thresholds = AlertThreshold::where('is_active', true)
                ->orderBy('min_value')
                ->get();
            
            // Default status if no threshold matches
            $defaultStatus = 'unknown';
            
            DB::beginTransaction();
            try {
                foreach ($sensorLocations as $location) {
                    // Generate random AQI value based on baseline and fluctuation range
                    $minValue = max(0, $settings->aqi_baseline - $settings->fluctuation_range);
                    $maxValue = min(500, $settings->aqi_baseline + $settings->fluctuation_range);
                    $aqiValue = round(mt_rand($minValue * 10, $maxValue * 10) / 10, 1); // For decimal precision
                    
                    // Determine status based on thresholds
                    $status = $defaultStatus;
                    $matchingThreshold = null;
                    
                    foreach ($thresholds as $threshold) {
                        if ($aqiValue >= $threshold->min_value && $aqiValue <= $threshold->max_value) {
                            $status = $threshold->level;
                            $matchingThreshold = $threshold;
                            break;
                        }
                    }
                    
                    // Create AQI reading
                    $reading = AqiReading::create([
                        'sensor_location_id' => $location->id,
                        'value' => $aqiValue,
                        'status' => $status,
                        'is_simulated' => true,
                    ]);
                    
                    if ($matchingThreshold && $matchingThreshold->level != 'good') {
                        Alert::create([
                            'sensor_location_id' => $location->id,
                            'alert_threshold_id' => $matchingThreshold->id,
                            'aqi_value' => $aqiValue,
                            'is_read' => false,
                        ]);
                    }
                }
                
                DB::commit();
                return [
                    'status' => 'success', 
                    'message' => 'Readings generated successfully'
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error generating readings: ' . $e->getMessage());
                return [
                    'status' => 'error', 
                    'message' => 'Failed to generate readings: ' . $e->getMessage()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in simulation: ' . $e->getMessage());
            return [
                'status' => 'error', 
                'message' => 'Simulation error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * API endpoint for generating readings
     */
    public function apiGenerateReadings()
    {
        $result = $this->generateReadings();
        
        if ($result['status'] === 'success') {
            return response()->json(['message' => $result['message']], 200);
        } else {
            return response()->json(['message' => $result['message']], 400);
        }
    }

    //this is for AQI gen simulate with the location
public function startSimulationForLocation($locationId)
{
    try {
        $location = SensorLocation::findOrFail($locationId);
        $settings = SimulationSetting::first();


        $minValue = max(0, $settings->aqi_baseline - $settings->fluctuation_range);
        $maxValue = min(500, $settings->aqi_baseline + $settings->fluctuation_range);
        $aqiValue = round(mt_rand($minValue * 10, $maxValue * 10) / 10, 1);

        $thresholds = AlertThreshold::where('is_active', true)->orderBy('min_value')->get();
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
            'sensor_location_id' => $location->id,
            'value' => $aqiValue,
            'status' => $status,
            'is_simulated' => true,
        ]);

        if ($matchingThreshold && $matchingThreshold->level != 'good') {
            Alert::create([
                'sensor_location_id' => $location->id,
                'alert_threshold_id' => $matchingThreshold->id,
                'aqi_value' => $aqiValue,
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Simulated AQI data for ' . $location->name);

    } catch (\Exception $e) {
        Log::error('Error simulating for location: ' . $e->getMessage());
        return redirect()->back()->with('success', 'Simulated AQI data for ' . $location->name);
    }
}


}