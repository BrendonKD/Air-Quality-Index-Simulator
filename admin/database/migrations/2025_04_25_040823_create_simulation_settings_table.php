<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('simulation_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('frequency')->default(1); // in minutes
            $table->integer('baseline_aqi')->default(50);
            $table->string('variation_pattern')->default('random'); // random, increasing, decreasing
            $table->boolean('is_running')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulation_settings');
    }
}
