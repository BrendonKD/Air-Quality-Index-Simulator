<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'frequency',        
        'baseline_aqi',     
        'variation_pattern', // Type of variation: random, increasing, decreasing
        'is_running',       // 1 = running, 0 = stopped
    ];
}
