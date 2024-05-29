<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenusFormation extends Model
{
    use HasFactory;

    protected $fillable = [
        'numchap',
        'numunite',
        'description',
        'nombreheures',
        'formation_id'
    ];

    public function formations()
    {
        return $this->belongsTo(Formations::class);
    }
}
