<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SimulationSetting;
use App\Http\Controllers\SimulationController;

class SimulateAqiReadings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate simulated AQI readings based on configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = SimulationSetting::first();
        
        if (!$settings || !$settings->is_running) {
            $this->info('Simulation is not running. Exiting.');
            return;
        }
        
        $this->info('Generating simulated AQI readings...');
        
        $controller = new SimulationController();
        $controller->generateReadings();
        
        $this->info('Simulated readings generated successfully.');
    }
}