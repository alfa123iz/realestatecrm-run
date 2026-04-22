<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AraziDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'arazi_id',
        'document_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function arazi()
    {
        return $this->belongsTo(Arazi::class);
    }
}
