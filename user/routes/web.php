<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AQIController;

// Route to display the AQI map
Route::get('/map', [AQIController::class, 'showMap'])->name('map.show');

// API route to fetch all sensors' AQI data
Route::get('/api/sensors/aqi', [AQIController::class, 'getSensorsAqi'])->name('api.sensors.aqi');

// API route to fetch AQI data for a specific sensor by ID
Route::get('/api/sensors/{id}/history', [AQIController::class, 'getSensorHistory'])->name('api.sensors.history');

// Route to display a dashboard (optional, as a potential feature)
Route::get('/dashboard', [AQIController::class, 'showDashboard'])->name('dashboard');
