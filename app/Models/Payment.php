<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'receipt_no',
        'source_table',
        'is_legacy',
        'registry_id',
        'kisan_id',
        'customer_id',
        'payment_type',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'is_legacy' => 'boolean',
    ];

    public function registry()
    {
        return $this->belongsTo(Registry::class);
    }

    public function kisan()
    {
        return $this->belongsTo(Kisan::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
