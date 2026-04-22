<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'investment_amount',
        'return_percentage',
        'invested_on',
        'status',
    ];

    protected $casts = [
        'invested_on' => 'date',
    ];
}
