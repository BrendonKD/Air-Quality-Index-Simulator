<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AlertThreshold
 *
 * @property int $id
 * @property string $level
 * @property float $min_value
 * @property float $max_value
 * @property string $color_code
 * @property string|null $description
 * @property bool $is_active
 */
class AlertThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'min_value',
        'max_value',
        'color_code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'min_value' => 'float',
        'max_value' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the alerts
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
