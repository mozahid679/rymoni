<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'tenant_id',
        'unit_id',
        'month',
        'rent_amount',
        'electricity_amount',
        'total_amount',
        'issue_date',
        'due_date',
        'status',
    ];

    protected $casts = [
        'rent_amount'        => 'decimal:2',
        'electricity_amount' => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'issue_date'         => 'date',
        'due_date'           => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function tenancy()
    {
        return Tenancy::where('tenant_id', $this->tenant_id)
            ->where('unit_id', $this->unit_id)
            ->latest('start_date')
            ->first();
    }

    // Future: one bill can have multiple payments (cash/bank/bkash/nagad)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    // Calculate total dynamically if needed
    public function calculateTotal()
    {
        return $this->rent_amount + $this->electricity_amount;
    }

    // Check status helpers (nice for UI badges)
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPartial()
    {
        return $this->status === 'partial';
    }

    public function isUnpaid()
    {
        return $this->status === 'unpaid';
    }

    public function paidAmount()
    {
        return $this->payments()->sum('amount');
    }

    public function dueAmount()
    {
        return max(0, $this->total_amount - $this->paidAmount());
    }
}
