<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAqiRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('aqi_records', function (Blueprint $table) {
            $table->id();
            $table->string('area_name', 50);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->integer('aqi')->nullable();
            $table->float('pm25')->nullable();
            $table->string('sensor_name', 50)->nullable();
            $table->date('record_date')->nullable();
            $table->dateTime('record_time')->nullable();
            $table->string('source', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aqi_records');
    }
}