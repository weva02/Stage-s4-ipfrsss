<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'nni',
        'nomprenom',
        'diplome',
        'genre',
        'lieunaissance',
        'adress',
        'age',
        'email',
        'phone',
        'wtsp',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}