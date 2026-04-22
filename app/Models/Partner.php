<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'share_percentage',
        'capital_amount',
        'joined_on',
        'status',
    ];

    protected $casts = [
        'joined_on' => 'date',
    ];
}
