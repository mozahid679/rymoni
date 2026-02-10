<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_no',
        'type',
        'monthly_rent',
        'has_electricity',
    ];

    protected $casts = [
        'monthly_rent'   => 'decimal:2',
        'has_electricity' => 'boolean',
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

    public function tenancies()
    {
        return $this->hasMany(Tenancy::class);
    }

    public function activeTenancy()
    {
        return $this->hasOne(Tenancy::class)
            ->whereNull('end_date');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers / Scopes
    |--------------------------------------------------------------------------
    */

    public function isShop(): bool
    {
        return $this->type === 'shop';
    }
    public function scopeShops($query)
    {
        return $query->where('type', 'shop');
    }

    public function meter()
    {
        return $this->hasOne(Meter::class);
    }

    public function isRoom(): bool
    {
        return $this->type === 'room';
    }
}
