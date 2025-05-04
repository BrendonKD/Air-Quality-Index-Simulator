<?php


namespace App\Console;

use App\Services\AirQualityService;
use App\Models\SensorLocation;
use App\Models\SimulationSetting;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\SimulateAQIData;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $routeMiddleware = [
        // Other middleware...
       // 'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.admin' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
       // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // Remove the 'admin' middleware if it exists and use auth:admin instead
        'admin' => \App\Http\Middleware\AdminMiddleware::class,

        $schedule->command('simulate:aqi-data')->everyMinute(),

    ];
    protected function schedule(Schedule $schedule): void
    {
        $settings = SimulationSetting::first();

        if ($settings && $settings->is_running) {
            $frequencySeconds = $settings->frequency_seconds;

            // Convert frequency (seconds) to minutes for scheduling
            $frequencyMinutes = ceil($frequencySeconds / 60);

            $schedule->call(function () {
                $locations = SensorLocation::all();
                foreach ($locations as $location) {
                    simulateAQIData::dispatch($location);
                }
            })->everyTenMinutes();
        }
    }


    
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


}
?>