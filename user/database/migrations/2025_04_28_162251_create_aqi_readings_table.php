<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aqi_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_location_id')->constrained('sensor_locations')->onDelete('cascade');
            $table->integer('value');
            $table->string('status'); // e.g., 'Good', 'Moderate', etc.
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aqi_readings');
    }
};