<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'amount',
        'method',
        'payment_date',
        'received_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isCash()
    {
        return $this->method === 'cash';
    }

    public function isBank()
    {
        return $this->method === 'bank';
    }

    public function isBkash()
    {
        return $this->method === 'bkash';
    }

    public function isNagad()
    {
        return $this->method === 'nagad';
    }
}
