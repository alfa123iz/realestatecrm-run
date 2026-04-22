<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registry extends Model
{
    use HasFactory;

    protected $fillable = [
        'registry_code',
        'customer_reg_no',
        'customer_id',
        'arazi_id',
        'agent_id',
        'check_by_agent_id',
        'registry_date',
        'booking_mode',
        'land_size',
        'registry_amount',
        'payment_words',
        'id_card_no',
        'witness_name',
        'nominee_name',
        'broker_commission',
        'advance_amount',
        'installment_amount',
        'down_payment',
        'due_date',
        'expected_registry_date',
        'status',
        'payment_status',
        'lock_status',
    ];

    protected $casts = [
        'registry_date' => 'date',
        'due_date' => 'date',
        'expected_registry_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function arazi()
    {
        return $this->belongsTo(Arazi::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function checkByAgent()
    {
        return $this->belongsTo(Agent::class, 'check_by_agent_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
