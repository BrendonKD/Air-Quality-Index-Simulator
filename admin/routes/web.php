<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\Admin\SensorLocationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AqiReadingController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\DataSimulationController;


// Admin Routes
Route::prefix('admin')->group(function() {
    // Guest routes
    Route::middleware(['guest:admin'])->group(function() {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [LoginController::class, 'login'])->name('admin.login.submit');
        Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('admin.register');
        Route::post('/register', [LoginController::class, 'register'])->name('admin.register.submit');
        Route::get('/sensors/locations', [SensorLocationController::class, 'getLocations'])->name('admin.sensors.locations');
        Route::get('/sensors/status', [StatusController::class, 'index'])->name('admin.sensors.status');
        //new sim AQI
        Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation.index');
        Route::post('/simulation/settings', [SimulationController::class, 'saveSettings'])->name('simulation.settings.save');
        Route::post('/simulation/start', [SimulationController::class, 'startSimulation'])->name('simulation.start');
        Route::post('/simulation/stop', [SimulationController::class, 'stopSimulation'])->name('simulation.stop');

    });
    
    // Protected routes
    Route::middleware(['auth:admin'])->group(function() {
        Route::get('/dashboard', function() {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

        // Sensor routes
        Route::get('/sensors', [SensorLocationController::class, 'index'])->name('admin.sensors.index');        
        Route::get('/sensors/create', [SensorLocationController::class, 'create'])->name('admin.sensors.create');
        Route::post('/sensors', [SensorLocationController::class, 'store'])->name('admin.sensors.store');
        Route::get('/sensors/{sensor}', [SensorLocationController::class, 'show'])->name('admin.sensors.show');
        Route::get('/sensors/{sensor}/edit', [SensorLocationController::class, 'edit'])->name('admin.sensors.edit');
        Route::put('/sensors/{sensor}', [SensorLocationController::class, 'update'])->name('admin.sensors.update');
        Route::delete('/sensors/{sensor}', [SensorLocationController::class, 'destroy'])->name('admin.sensors.destroy');
        Route::patch('/sensors/{sensor}/toggle-active', [SensorLocationController::class, 'toggleActive'])->name('admin.sensors.toggle-active');
        Route::get('/sensors/locations', [SensorLocationController::class, 'getLocations'])->name('admin.sensors.locations');


        //admin managers CRUD
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

        // Simulation routes - add new route for simulation.simulation
        Route::get('/simulation', [SimulationController::class, 'index'])->name('admin.simulation');
        Route::get('/simulation/simulation', [SimulationController::class, 'index'])->name('admin.simulation.simulation');
        Route::get('/simulation/readings', [AqiReadingController::class, 'index'])->name('admin.simulation.readings');
        Route::get('/simulation/active', [AqiReadingController::class, 'index'])->name('admin.simulation.active');
        Route::post('/simulation/update', [SimulationController::class, 'updateSettings'])->name('admin.simulation.update');
        Route::post('/simulation/start', [SimulationController::class, 'startSimulation'])->name('admin.simulation.start');
        Route::post('/simulation/stop', [SimulationController::class, 'stopSimulation'])->name('admin.simulation.stop');
        Route::post('/simulation/generate', [SimulationController::class, 'generateReadings'])->name('admin.simulation.generate');
        
        // Data Simulation Management Routes
        Route::get('/data-simulation', function () {
            return view('admin.simulation.data_simulation_management');
        })->name('admin.data.simulation');  
        // Alert routes
        Route::get('/thresholds', [AlertController::class, 'index'])->name('admin.alerts.thresholds'); // Displays thresholds
        Route::post('/thresholds', [AlertController::class, 'storeThreshold'])->name('admin.threshold.store'); // Creates/updates thresholds
        Route::delete('/thresholds/{id}', [AlertController::class, 'deleteThreshold'])->name('admin.alerts.delete'); // Deletes thresholds
        Route::get('/active', [AlertController::class, 'activeAlerts'])->name('admin.alerts.active');
        Route::post('/{id}/mark-read', [AlertController::class, 'markAsRead'])->name('admin.alerts.mark-read');
        Route::get('/count', [AlertController::class, 'getAlertsCount'])->name('admin.alerts.count');
        
        // AQI readings routes
        Route::get('/readings', [AqiReadingController::class, 'index'])->name('admin.readings');
        Route::get('/readings/latest', [AqiReadingController::class, 'getLatestReadings'])->name('admin.readings.latest');
        Route::get('/readings/history/{sensorId}', [AqiReadingController::class, 'getReadingsHistory'])->name('admin.readings.history');

        //test simulate AQIgen
        Route::post('/simulate/location/{locationId}', [SimulationController::class, 'startSimulationForLocation'])->name('simulate.location');
        Route::get('/admin/simulation', [SimulationController::class, 'index'])->name('simulation.AQIgen');
        Route::post('/simulate/all', [SimulationController::class, 'simulateAllLocations'])->name('simulate.all.locations');

    });
});

// Redirect root to admin login
Route::get('/', function() {
    return redirect()->route('admin.login');
});