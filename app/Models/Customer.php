<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'legacy_customer_code',
        'name',
        'mobile',
        'secondary_mobile',
        'id_document_no',
        'address',
    ];

    public function registries()
    {
        return $this->hasMany(Registry::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
