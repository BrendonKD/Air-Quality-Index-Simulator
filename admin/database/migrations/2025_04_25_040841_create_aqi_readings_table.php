<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAqiReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aqi_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('sensor_locations');
            $table->decimal('value', 8, 2);
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            // Add index for faster queries
            $table->index(['location_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aqi_readings');
    }
}