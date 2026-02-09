<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    // Relationships

    public function tenancies()
    {
        return $this->hasMany(Tenancy::class);
    }

    // Optional helper (current active tenancy)
    public function activeTenancy()
    {
        return $this->hasOne(Tenancy::class)
            ->whereNull('end_date')
            ->latestOfMany();
    }
}
