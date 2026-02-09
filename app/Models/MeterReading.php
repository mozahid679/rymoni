<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_id',
        'month',
        'previous_unit',
        'current_unit',
        'per_unit_price',
    ];

    protected $casts = [
        'previous_unit'   => 'integer',
        'current_unit'    => 'integer',
        'per_unit_price'  => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function meter()
    {
        return $this->belongsTo(Meter::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Attributes
    |--------------------------------------------------------------------------
    | These are helpers, NOT stored in DB
    */

    public function getTotalUnitsAttribute(): int
    {
        return max(0, $this->current_unit - $this->previous_unit);
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->total_units * (float) $this->per_unit_price;
    }
}
