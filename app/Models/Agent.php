<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_code',
        'name',
        'rank_title',
        'mobile',
        'commission_percentage',
        'legacy_percent',
        'sponsor_agent_id',
    ];

    public function registries()
    {
        return $this->hasMany(Registry::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(self::class, 'sponsor_agent_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(self::class, 'sponsor_agent_id');
    }
}
