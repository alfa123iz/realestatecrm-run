<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBondPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registry_id',
        'customer_id',
        'entry_no',
        'entry_date',
        'entry_type',
        'amount',
        'payment_method',
        'remarks',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function registry()
    {
        return $this->belongsTo(Registry::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
