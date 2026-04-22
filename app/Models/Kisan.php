<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_no',
        'legacy_arazi_no',
        'amount',
        'location',
        'name',
        'mobile',
        'address',
    ];

    public function arazis()
    {
        return $this->hasMany(Arazi::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
