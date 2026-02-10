<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Meter extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'meter_no',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function readings()
    {
        return $this->hasMany(MeterReading::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function lastReading(): HasOne
    {
        return $this->hasOne(MeterReading::class)->latestOfMany();
    }
}
